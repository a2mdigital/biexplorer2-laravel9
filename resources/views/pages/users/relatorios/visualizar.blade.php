@extends('layout.relatorio-users.master') 
@section('link-voltar-relatorios')
<a href="{{route('users.tenant.relatorios', $relatorio->subgrupo_relatorio_id)}}" class="nav-link">
          <i class="link-icon" data-feather="arrow-left-circle"></i>
          <span class="link-title">{{__('menu.back_to_reports')}}</span>
</a>
@endsection
@push('plugin-styles')
<style>
.page-content{
    padding: 0.5rem !important;
}
.powerBIRelatorio{
    width: 100%;
    height: 100vh;

}
iframe{
     border-style: none; 
    /*border: 1px solid; */
}
</style>
@endpush
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<input type="hidden" name="tipo_token" id="tipo_token" value="{{$tipo_token}}">
<input type="hidden" name="token" id="token" value="{{$token}}">
<input type="hidden" name="expires_in" id="expires_in" value="{{$expires_in}}">
<input type="hidden" name="time_update_token" id="time_update_token" value="">
<input type="hidden" name="tipo" id="tipo" value="{{$relatorio->tipo}}">
<input type="hidden" name="report_id" id="report_id" value="{{$relatorio->report_id}}">
<input type="hidden" name="workspace_id" id="workspace_id" value="{{$relatorio->workspace_id}}">
<input type="hidden" name="dataset_id" id="dataset_id" value="{{$relatorio->dataset_id}}">
<input type="hidden" name="habilita_filtro_lateral" id="habilita_filtro_lateral" value="{{$relatorio->filtro_lateral}}">
<input type="hidden" name="ignora_filtro_rls" id="ignora_filtro_rls" value="{{$relatorio->ignora_filtro_rls}}">
<!-- REGRA DE FILTRO E RLS POR TENANT --> 
<input type="hidden" name="utiliza_rls_tenant" id="utiliza_rls_tenant" value="{{$tenant->utiliza_rls}}">
<!-- FILTROS EMPRESA -->
<input type="hidden" name="utiliza_filtro_tenant" id="utiliza_filtro_tenant" value="{{$tenant->utiliza_filtro}}">
<input type="hidden" name="filtro_tabela_tenant" id="filtro_tabela_tenant" value="{{$tenant->filtro_tabela}}">
<input type="hidden" name="filtro_coluna_tenant" id="filtro_coluna_tenant" value="{{$tenant->filtro_coluna}}">
<input type="hidden" name="filtro_valor_tenant" id="filtro_valor_tenant" value="{{$tenant->filtro_valor}}">

@if(isset($relatorios_user))
<!-- RLS E FILTROS RELATORIO POR USUÁRIO-->
<!--FILTROS -->
<input type="hidden" name="filtro_tabela_relatorio_usuario" id="filtro_tabela_relatorio_usuario" value="{{$relatorios_user->filtro_tabela}}">
<input type="hidden" name="filtro_coluna_relatorio_usuario" id="filtro_coluna_relatorio_usuario" value="{{$relatorios_user->filtro_coluna}}">
<input type="hidden" name="filtro_valor_relatorio_usuario" id="filtro_valor_relatorio_usuario" value="{{$relatorios_user->filtro_valor}}">
<!-- RLS --> 
<input type="hidden" name="regra_rls_relatorio_usuario" id="regra_rls_relatorio_usuario" value="{{$relatorios_user->regra_rls}}">
<input type="hidden" name="username_rls_relatorio_usuario" id="username_rls_relatorio_usuario" value="{{$relatorios_user->username_rls}}">
@endif
@if(isset($relatorios_departamento))
<!-- RLS E FILTROS RELATORIO POR DEPARTAMENTO-->
<!--FILTROS -->
<input type="hidden" name="filtro_tabela_relatorio_departamento" id="filtro_tabela_relatorio_departamento" value="{{$relatorios_departamento->filtro_tabela}}">
<input type="hidden" name="filtro_coluna_relatorio_departamento" id="filtro_coluna_relatorio_departamento" value="{{$relatorios_departamento->filtro_coluna}}">
<input type="hidden" name="filtro_valor_relatorio_departamento" id="filtro_valor_relatorio_departamento" value="{{$relatorios_departamento->filtro_valor}}">
<!-- RLS --> 
<input type="hidden" name="regra_rls_relatorio_departamento" id="regra_rls_relatorio_departamento" value="{{$relatorios_departamento->regra_rls}}">
<input type="hidden" name="username_rls_relatorio_departamento" id="username_rls_relatorio_departamento" value="{{$relatorios_departamento->username_rls}}">
@endif
<!-- DADOS DO DEPARTAMENTO --> 
<input type="hidden" name="filtro_tabela_departamento" id="filtro_tabela_departamento" value="{{$departamento->filtro_tabela}}">
<input type="hidden" name="filtro_coluna_departamento" id="filtro_coluna_departamento" value="{{$departamento->filtro_coluna}}">
<input type="hidden" name="filtro_valor_departamento" id="filtro_valor_departamento" value="{{$departamento->filtro_valor}}">
<!-- DADOS DO FILTRO USUARIO --> 
<input type="hidden" name="filtro_tabela_usuario" id="filtro_tabela_usuario" value="{{$user->filtro_tabela}}">
<input type="hidden" name="filtro_coluna_usuario" id="filtro_coluna_usuario" value="{{$user->filtro_coluna}}">
<input type="hidden" name="filtro_valor_usuario" id="filtro_valor_usuario" value="{{$user->filtro_valor}}">
<!-- RLS DO USUÁRIO --> 
<input type="hidden" name="regra_rls_usuario" id="regra_rls_usuario" value="{{$user->regra_rls}}">
<input type="hidden" name="username_rls_usuario" id="username_rls_usuario" value="{{$user->username_rls}}">

<!-- REGRA DE FILTROS E RLS -->
<input type="hidden" name="regra_filtro_rls" id="regra_filtro_rls" value="{{$regra}}">
<!-- DIV PARA MOSTRAR RELATÓRIO DO POWER BI -->
<div id="powerBI" class="powerBIRelatorio"></div>
<!-- FIM DIV POWER BI -->

@endsection

@push('plugin-scripts')
  <!-- Plugin js import here -->
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/powerbi.js') }}"></script>
<script src="{{ asset('assets/js/powerbi-models.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.powerbi.js') }}"></script>
<script type="text/javascript">
    //FULL SCREEN
    function fullscreen() {
        // Get a reference to the embedded report HTML element
        var embedContainer = $('#powerBI')[0];
        // Get a reference to the embedded report.
        report = powerbi.get(embedContainer);
        // Displays the report in full screen mode.
        report.fullscreen();
    }
    //ATUALIZAR
    function atualizar() {
        getToken();
      // Get a reference to the embedded report HTML element
        var embedContainer = $('#powerBI')[0];
        // Get a reference to the embedded report.
        report = powerbi.get(embedContainer);
        // Displays the report in full screen mode.
        report.refresh();
    }
 
    function getToken(){
       var tipo_token = $('#tipo_token').val(); 
       var regra_rls = $('#regra_filtro_rls').val(); 
       if(regra_rls == 'sem_filtro_rls'){
        $.ajax({
          headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "GET",
                    url:'{{route("users.tentant.powerbi.getToken")}}',
          success:function(data){
              var resposta = data.resposta;
              if(resposta == 'ok'){
                var tokenRecebido = data.token;
                var expires_in = data.expires_in;
                updateToken(tokenRecebido);
                $('#expires_in').val(expires_in);
              }
          }

       });
       
       }
     
    }

    function updateToken(newToken){
       // Get a reference to the embedded report HTML element
       var embedContainer = $('#powerBI')[0];
        // Get a reference to the embedded report.
        report = powerbi.get(embedContainer);
        // Displays the report in full screen mode.
        report.setAccessToken(newToken);
        report.refresh();
    }
    

    $(document).ready(function() {
       //hora que abriu o relatório e atualizou o token
        $('#time_update_token').val(Date.now());
        var token = $('#token').val();
        var expires_in = $('#expires_in').val();
        var report_id = $('#report_id').val(); 
        var workspace_id = $('#workspace_id').val(); 
        var tipo = $('#tipo').val(); 
        /*REGRA DE FILTROS */ 
        var regra_tenant = $('#regra_tenant').val();
        var utiliza_rls_tenant = $('#utiliza_rls_tenant').val();
        var regra_filtro_rls = $('#regra_filtro_rls').val(); 
        var ignora_rls_empresa =  $('#ignora_filtro_rls').val(); 
        /*FILTROS DE TENANT */
        var utiliza_filtro_tenant = $('#utiliza_filtro_tenant').val(); 
      
      //UPDATE TOKEN POWER BI EMBEDDED
      const minutes_before_expiration = 10;
      //verificar o token a cada 30 segundos
      const interval_time = 30000;
      //pegar o token expirado
     
      
      if(regra_filtro_rls == 'sem_filtro_rls'){
      setInterval(()=>checkTokenAndUpdate(report_id, workspace_id), interval_time);
      }

      function checkTokenAndUpdate(report_id, workspace_id){
        //hora que atualizou o relatório
        var time_update_token = parseInt($('#time_update_token').val());
        //tempo para expirar token  
        var expires_in = $('#expires_in').val();
        var tokenExpiration = expires_in * 1000;
        //tempo para expirar o token
        const timeToExpiration = time_update_token + tokenExpiration;
        //pegar data atual
        const currentTime = Date.now();
       // const expiration = Date.parse(tokenExpiration);
        //verificar o tempo até o token expirar
        //const timeUntilExpiration = tokenExpiration - currentTime;
        const timeUntilExpiration = timeToExpiration - currentTime;
        const timeToUpdate = minutes_before_expiration * 60 * 1000;

        //atualiza o token se estiver expirado
        if(timeUntilExpiration <= timeToUpdate){
          console.log("Atualizando o token");
          $('#time_update_token').val(Date.now());
          getToken();
        }else{
          /*
          console.log('não atualiza o token ainda');
          console.log('expires_in ' + expires_in);
          console.log('timeUntilExpiration ' + timeUntilExpiration);
          console.log('timeToUpdate ' + timeToUpdate);
          */
        }
      }

       
        //verica se esta habilitado o filtro lateral
        var habilita_filtro_lateral = $('#habilita_filtro_lateral').val();
        var filtro_lateral = false;
        if(habilita_filtro_lateral == 'S'){
            var filtro_lateral = true;
        }else{
            var filtro_lateral = false;
        }
       
        if(tipo == 'relatorio'){ 
          //VERIFICO PRIMEIRO O RLS DO TENANT 
          if(utiliza_rls_tenant == 'S' && ignora_rls_empresa != 'S'){
          
            //ABRIR RLS POR TENANT
            
            abrirRelatorioRLS(token, report_id, filtro_lateral);
          }else if(utiliza_filtro_tenant == 'S'){
          
          //ABRIR RELATÓRIO COM FILTRO DE EMPRESA
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
            var filtros_tenant = {
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
            };
            switch(regra_filtro_rls){
              case 'filtro_relatorio_usuario':
            
                var filtro_tabela_relatorio_usuario = $('#filtro_tabela_relatorio_usuario').val();
                var filtro_coluna_relatorio_usuario = $('#filtro_coluna_relatorio_usuario').val();
                var filtro_valor_relatorio_usuario = $('#filtro_valor_relatorio_usuario').val().toString().split(',');
                /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
                var array_valor_relatorio_usuario  = [];
                $(filtro_valor_relatorio_usuario).each(function(key, value) {
                  if($.isNumeric(value)){
                          array_valor_relatorio_usuario.push(parseInt(value))
                        }else{
                          array_valor_relatorio_usuario.push(value)
                        }
                });
                  var filtros_relatorio_usuario = {
                              $schema: "http://powerbi.com/product/schema#basic",
                              target: {
                                          table: filtro_tabela_relatorio_usuario,
                                          column: filtro_coluna_relatorio_usuario
                                      },
                              operator: "In",
                              values: array_valor_relatorio_usuario,
                              displaySettings: {
                                  isLockedInViewMode: true
                              }
                  };
                  var filtros = [filtros_tenant, filtros_relatorio_usuario];
                  abrirRelatorioFiltroTenant(token, report_id, filtro_lateral, filtros);
                break;
              case 'filtro_relatorio_departamento': 
             
                  var filtro_tabela_departamento = $('#filtro_tabela_departamento').val();
                  var filtro_coluna_departamento = $('#filtro_coluna_departamento').val();
                  var filtro_valor_departamento = $('#filtro_valor_departamento').val().toString().split(',');
                  /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
                  var array_valor_relatorio_departamento  = [];
                  $(filtro_valor_departamento).each(function(key, value) {
                    if($.isNumeric(value)){
                      array_valor_relatorio_departamento.push(parseInt(value))
                    }else{
                      array_valor_relatorio_departamento.push(value)
                    }
                  });
                  var filtros_relatorio_departamento = {
                              $schema: "http://powerbi.com/product/schema#basic",
                              target: {
                                          table: filtro_tabela_departamento,
                                          column: filtro_coluna_departamento
                                      },
                              operator: "In",
                              values: array_valor_relatorio_departamento,
                              displaySettings: {
                                  isLockedInViewMode: true
                              }
                  };
                  var filtros = [filtros_tenant, filtros_relatorio_departamento];
                  abrirRelatorioFiltroTenant(token, report_id, filtro_lateral, filtros);
                break;
              case 'filtro_usuario': 
             
                var filtro_tabela_usuario = $('#filtro_tabela_usuario').val();
                var filtro_coluna_usuario = $('#filtro_coluna_usuario').val();
                var filtro_valor_usuario = $('#filtro_valor_usuario').val().toString().split(',');
                /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
                var array_valor_usuario  = [];
                $(filtro_valor_usuario).each(function(key, value) {
                  if($.isNumeric(value)){
                    array_valor_usuario.push(parseInt(value))
                  }else{
                    array_valor_usuario.push(value)
                  }
                });
                var filtros_por_usuario = {
                              $schema: "http://powerbi.com/product/schema#basic",
                              target: {
                                          table: filtro_tabela_usuario,
                                          column: filtro_coluna_usuario
                                      },
                              operator: "In",
                              values: array_valor_usuario,
                              displaySettings: {
                                  isLockedInViewMode: true
                              }
                  };
                  var filtros = [filtros_tenant, filtros_por_usuario];
                  abrirRelatorioFiltroTenant(token, report_id, filtro_lateral, filtros);
                break;  
              case 'filtro_departamento': 
           
                var filtro_tabela_departamento = $('#filtro_tabela_departamento').val();
                var filtro_coluna_departamento = $('#filtro_coluna_departamento').val();
                var filtro_valor_departamento = $('#filtro_valor_departamento').val().toString().split(',');
                /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
                var array_valor_departamento  = [];
                $(filtro_valor_departamento).each(function(key, value) {
                  if($.isNumeric(value)){
                    array_valor_departamento.push(parseInt(value))
                  }else{
                    array_valor_departamento.push(value)
                  }
                });
                var filtros_por_departamento = {
                              $schema: "http://powerbi.com/product/schema#basic",
                              target: {
                                          table: filtro_tabela_departamento,
                                          column: filtro_coluna_departamento
                                      },
                              operator: "In",
                              values: array_valor_departamento,
                              displaySettings: {
                                  isLockedInViewMode: true
                              }
                  };
                  var filtros = [filtros_tenant, filtros_por_departamento];
                  abrirRelatorioFiltroTenant(token, report_id, filtro_lateral, filtros);
                break;  
              default: 
         
              abrirRelatorioComFiltro(token, report_id, filtro_lateral, filtro_tabela_tenant, filtro_coluna_tenant, array_empresa_formatado);
               break;
            }
          
          }else{
        
            //ABRIR RELATÓRIO SEM FILTRO E RLS DE TENANT
            //VER AGORA OS FILTROS E RLS DOS USUÁRIOS E DEPARTAMENTOS
            switch(regra_filtro_rls){
            case 'rls_relatorio_usuario':
            case 'rls_relatorio_departamento': 
            case 'rls_usuario': 
            console.log("case rls relatorio");
             //ABRIR RELATÓRIO COM RLS
              abrirRelatorioRLS(token, report_id, filtro_lateral);
            break;
            case 'filtro_relatorio_usuario': 
           
              var filtro_tabela_relatorio_usuario = $('#filtro_tabela_relatorio_usuario').val();
              var filtro_coluna_relatorio_usuario = $('#filtro_coluna_relatorio_usuario').val();
              var filtro_valor_relatorio_usuario = $('#filtro_valor_relatorio_usuario').val().toString().split(',');
              /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
              var array_valor_relatorio_usuario  = [];
              $(filtro_valor_relatorio_usuario).each(function(key, value) {
                if($.isNumeric(value)){
                        array_valor_relatorio_usuario.push(parseInt(value))
                      }else{
                        array_valor_relatorio_usuario.push(value)
                      }
              });
            abrirRelatorioComFiltro(token, report_id, filtro_lateral, filtro_tabela_relatorio_usuario, filtro_coluna_relatorio_usuario, array_valor_relatorio_usuario);
            break;  
            case 'filtro_relatorio_departamento': 
           
              var filtro_tabela_departamento = $('#filtro_tabela_departamento').val();
              var filtro_coluna_departamento = $('#filtro_coluna_departamento').val();
              var filtro_valor_departamento = $('#filtro_valor_departamento').val().toString().split(',');
              /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
              var array_valor_relatorio_departamento  = [];
              $(filtro_valor_departamento).each(function(key, value) {
                if($.isNumeric(value)){
                  array_valor_relatorio_departamento.push(parseInt(value))
                 }else{
                  array_valor_relatorio_departamento.push(value)
                 }
              });
              abrirRelatorioComFiltro(token, report_id, filtro_lateral, filtro_tabela_departamento, filtro_coluna_departamento, array_valor_relatorio_departamento);
            break;  
            case 'filtro_usuario': 
           
              var filtro_tabela_usuario = $('#filtro_tabela_usuario').val();
              var filtro_coluna_usuario = $('#filtro_coluna_usuario').val();
              var filtro_valor_usuario = $('#filtro_valor_usuario').val().toString().split(',');
              /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
              var array_valor_usuario  = [];
              $(filtro_valor_usuario).each(function(key, value) {
                if($.isNumeric(value)){
                  array_valor_usuario.push(parseInt(value))
                 }else{
                  array_valor_usuario.push(value)
                 }
              });
              abrirRelatorioComFiltro(token, report_id, filtro_lateral, filtro_tabela_usuario, filtro_coluna_usuario, array_valor_usuario);
            break;  
            case 'filtro_departamento': 
          
              var filtro_tabela_departamento = $('#filtro_tabela_departamento').val();
              var filtro_coluna_departamento = $('#filtro_coluna_departamento').val();
              var filtro_valor_departamento = $('#filtro_valor_departamento').val().toString().split(',');
              /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
              var array_valor_departamento  = [];
              $(filtro_valor_departamento).each(function(key, value) {
                if($.isNumeric(value)){
                  array_valor_departamento.push(parseInt(value))
                 }else{
                  array_valor_departamento.push(value)
                 }
              });
              abrirRelatorioComFiltro(token, report_id, filtro_lateral, filtro_tabela_departamento, filtro_coluna_departamento, array_valor_departamento);
            break;  
            default: 
          
             abrirRelatorioSemFiltro(token, report_id, filtro_lateral);
            break;  
            }
          
          
          }
   
        }else if(tipo == 'dashboard'){ //ELSE TIPO == RELATÓRIO
          abrirDashboard(token, report_id)
        }
        function abrirRelatorioRLS(token, report_id, filtro_lateral){
           //MOSTRAR RELATÓRIO POWER BI COM RLS
           
          console.log("funcion abrir Relatorio RLS");
            //VERIFICAR SE TEM FILTRO
            var regra_filtro_rls = $('#regra_filtro_rls').val(); 
            console.log("Regra: " + regra_filtro_rls);
            switch(regra_filtro_rls){
              case 'filtro_relatorio_usuario':
            
                var filtro_tabela_relatorio_usuario = $('#filtro_tabela_relatorio_usuario').val();
                var filtro_coluna_relatorio_usuario = $('#filtro_coluna_relatorio_usuario').val();
                var filtro_valor_relatorio_usuario = $('#filtro_valor_relatorio_usuario').val().toString().split(',');
                /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
                var array_valor_relatorio_usuario  = [];
                $(filtro_valor_relatorio_usuario).each(function(key, value) {
                  if($.isNumeric(value)){
                          array_valor_relatorio_usuario.push(parseInt(value))
                        }else{
                          array_valor_relatorio_usuario.push(value)
                        }
                });
                  var filtros_relatorio_usuario = {
                              $schema: "http://powerbi.com/product/schema#basic",
                              target: {
                                          table: filtro_tabela_relatorio_usuario,
                                          column: filtro_coluna_relatorio_usuario
                                      },
                              operator: "In",
                              values: array_valor_relatorio_usuario,
                              displaySettings: {
                                  isLockedInViewMode: true
                              }
                  };
                  var filtros = [filtros_relatorio_usuario];
       
                break;
              case 'filtro_relatorio_departamento': 
             
                  var filtro_tabela_departamento = $('#filtro_tabela_departamento').val();
                  var filtro_coluna_departamento = $('#filtro_coluna_departamento').val();
                  var filtro_valor_departamento = $('#filtro_valor_departamento').val().toString().split(',');
                  /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
                  var array_valor_relatorio_departamento  = [];
                  $(filtro_valor_departamento).each(function(key, value) {
                    if($.isNumeric(value)){
                      array_valor_relatorio_departamento.push(parseInt(value))
                    }else{
                      array_valor_relatorio_departamento.push(value)
                    }
                  });
                  var filtros_relatorio_departamento = {
                              $schema: "http://powerbi.com/product/schema#basic",
                              target: {
                                          table: filtro_tabela_departamento,
                                          column: filtro_coluna_departamento
                                      },
                              operator: "In",
                              values: array_valor_relatorio_departamento,
                              displaySettings: {
                                  isLockedInViewMode: true
                              }
                  };
                  var filtros = [filtros_relatorio_departamento];
         
                break;
              case 'filtro_usuario': 
               
                var filtro_tabela_usuario = $('#filtro_tabela_usuario').val();
                var filtro_coluna_usuario = $('#filtro_coluna_usuario').val();
                var filtro_valor_usuario = $('#filtro_valor_usuario').val().toString().split(',');
                /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
                var array_valor_usuario  = [];
                $(filtro_valor_usuario).each(function(key, value) {
                  if($.isNumeric(value)){
                    array_valor_usuario.push(parseInt(value))
                  }else{
                    array_valor_usuario.push(value)
                  }
                });
                var filtros_por_usuario = {
                              $schema: "http://powerbi.com/product/schema#basic",
                              target: {
                                          table: filtro_tabela_usuario,
                                          column: filtro_coluna_usuario
                                      },
                              operator: "In",
                              values: array_valor_usuario,
                              displaySettings: {
                                  isLockedInViewMode: true
                              }
                  };
                  var filtros = [filtros_por_usuario];
             
                break;  
              case 'filtro_departamento': 
           
                var filtro_tabela_departamento = $('#filtro_tabela_departamento').val();
                var filtro_coluna_departamento = $('#filtro_coluna_departamento').val();
                var filtro_valor_departamento = $('#filtro_valor_departamento').val().toString().split(',');
                /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
                var array_valor_departamento  = [];
                $(filtro_valor_departamento).each(function(key, value) {
                  if($.isNumeric(value)){
                    array_valor_departamento.push(parseInt(value))
                  }else{
                    array_valor_departamento.push(value)
                  }
                });
                var filtros_por_departamento = {
                              $schema: "http://powerbi.com/product/schema#basic",
                              target: {
                                          table: filtro_tabela_departamento,
                                          column: filtro_coluna_departamento
                                      },
                              operator: "In",
                              values: array_valor_departamento,
                              displaySettings: {
                                  isLockedInViewMode: true
                              }
                  };
                  var filtros = [filtros_por_departamento];
            
                break;  
                case 'rls_tenant_filtro_usuario': 
                /*
                  CAI NESSA CONDIÇÃO CASO O TENANT TENHA RLS 
                  E NO CADASTRO DO USUÁRIO TENHA ALGUM FILTRO
                */
               var filtro_tabela_usuario = $('#filtro_tabela_usuario').val();
               var filtro_coluna_usuario = $('#filtro_coluna_usuario').val();
               var filtro_valor_usuario = $('#filtro_valor_usuario').val().toString().split(',');
               /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
               var array_valor_usuario  = [];
               $(filtro_valor_usuario).each(function(key, value) {
                 if($.isNumeric(value)){
                   array_valor_usuario.push(parseInt(value))
                 }else{
                   array_valor_usuario.push(value)
                 }
               });
               var filtros_por_usuario = {
                             $schema: "http://powerbi.com/product/schema#basic",
                             target: {
                                         table: filtro_tabela_usuario,
                                         column: filtro_coluna_usuario
                                     },
                             operator: "In",
                             values: array_valor_usuario,
                             displaySettings: {
                                 isLockedInViewMode: true
                             }
                 };
                 var filtros = [filtros_por_usuario];
            
               break;  
                /*
             case 'rls_relatorio_usuario': 
             //RLS POR RELATÓRIO
              var regra_rls_relatorio = $('#regra_rls_relatorio_usuario').val();
              var username_rls_relatorio = $('#username_rls_relatorio_usuario').val();

              break;
              */
                default:
                 
                var filtros = [];

                break;
            }

             //CASO NÃO TIVER NENHUM FILTRO ABRO SÓ O RLS DO TENANT
             var models = window['powerbi-client'].models;
                var EmbedUrlReport = "https://app.powerbi.com/reportEmbed";
                var tokenTypeReport = 0;
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

            //FIM VERIFICAR SE TEM FILTROS TBM
            
        } 
        //FUNÇÕES DE ABRIR OS RELATÓRIOS
        //ABRIR RELATÓRIOS COM FILTRO DE TENANT + OUTROS FILTROS JUNTOS COMO DEPARTAMENTO, USUÁRIO,ETC..
        function abrirRelatorioFiltroTenant(token, report_id, filtro_lateral, filtros){
        
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
        
        }//FIM ABRIR FILTRO DE TENANT + (DEPARTAMENTO||USUARIO||RELATORIO)

        //ABRIR RELATÓRIO COM FILTRO
        function abrirRelatorioComFiltro(token, report_id, filtro_lateral, filtro_tabela, filtro_coluna, array_valor){
          
            var filtros = [{
                              $schema: "http://powerbi.com/product/schema#basic",
                              target: {
                                          table: filtro_tabela,
                                          column: filtro_coluna
                                      },
                              operator: "In",
                              values: array_valor,
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
            console.log("Filtros" + JSON.stringify(filtros, null, 4) );
            var embedContainer = $('#powerBI')[0];
            var report = powerbi.embed(embedContainer, config);
        } // FIM FUNCAO ABRIR RELATORIO COM FILTRO

      function abrirRelatorioSemFiltro(token, report_id, filtro_lateral){
    
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
        } //FIM FUNCAO ABRIR RELATORIO SEM FILTRO TENANT

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
@endpush