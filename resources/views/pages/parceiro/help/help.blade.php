@extends('layout.parceiros.master')
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"></li>
    <li class="breadcrumb-item active" aria-current="page">Configuração Inicial</li>
  </ol>
</nav>
<div class="row">
    <div class="col-sm-12">
        <center><h5>Configuração Inicial para funcionamento da Plataforma</h5></center>
    </div>
</div>
<br>
<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
         1 - Tornar-se Administrador do Power BI.
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
      <b>
       <p>1.1 - Acessar o <a href="https://app.powerbi.com" target="_blank">Power BI Service</a> com seu usuário e senha.</p>
       <br>
       <p>1.2 - Clicar no Menu do Power BI e acessar a opção Administração</p> 
       <br>
       <img src="{{asset('assets/images/help/1.png')}}" width="20%"/>
       <i data-feather="arrow-right-circle"></i>
       <img src="{{asset('assets/images/help/2.png')}}" width="20%"/>
       <br><br>
       <p>1.3 - Na central de Administração acesar o menu Configurar -> Dominios</p>
       <br>
       <img src="{{asset('assets/images/help/3.png')}}" width="40%"/>
       <br>
       </b>
       <p><b>OBS:</b>O Domínio da sua empresa deverá estar verificado (como na imagem), caso contrário será necessário adicionar e confirmar o domínio, seguindo os passos que aparecem na tela. Para esse passo será necessário conhecimento em DNS e hospedagem, falar com o TI de sua empresa para configurar. </p>
        <p>Após a verificação do domínio, seu usuário acaba de se tornar um Administrador, isso torna-se necessário para os passos abaixo no Portal Azure.</p>  

    </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
         2 - Registrar Aplicativo no Portal do Azure
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
      <b>    
      <p>Obs: Os passos abaixo deverão ser feitos após a verificação do dominio realizado no Passo 1</p>   
      <br> 
      <p>2.1 - Acessar o <a href="https://portal.azure.com" target="_blank">Portal Azure</a> com seu usuário e senha do Power BI.</p>
      <p>2.2 - Acessar o menu e ir em Azure Active Directory</p> 
      <br> <img src="{{asset('assets/images/help/4.png')}}" width="40%"/><br><br>   
      <p>2.3 - Clicar em Registros de Aplicativo</p> 
      <br> <img src="{{asset('assets/images/help/5.png')}}" width="40%"/><br><br>
      <p>2.4 - Clicar em Novo Registro</p> 
      <br> <img src="{{asset('assets/images/help/6.png')}}" width="60%"/><br><br>  
      <p>2.5 - Preencher as informações conforme a imagem abaixo</p> 
      <br> <img src="{{asset('assets/images/help/7.png')}}" width="60%"/><br><br> 
      <p>2.6 - Após criado o aplicativo anotar em algum lugar os dados ID do aplicativo, ID do diretório (Iremos utilizar depois)</p> 
      <br> <img src="{{asset('assets/images/help/8.png')}}" width="60%"/><br><br>   
      <p>2.7 - Clicar em Certificados e Segredos e após em Novo Segredo do Cliente</p> 
      <br> <img src="{{asset('assets/images/help/9.png')}}" width="60%"/><br><br> 
      <p>2.8 - Digite um nome e deixe a opção Expirar Senha como Nunca</p> 
      <br> <img src="{{asset('assets/images/help/10.png')}}" width="40%"/><br><br>  
      <p>2.9 - Copie o valor gerado e guarde em  um local seguro (Iremos utilizar depois)</p> 
      <br> <img src="{{asset('assets/images/help/11.png')}}" width="40%"/><br><br>  
      <p>2.10 - No Menu vá para Permissões de Api e após em Adicionar Permissão</p> 
      <br> <img src="{{asset('assets/images/help/12.png')}}" width="40%"/><br><br>
      <p>2.11 - Selecione a opção Power BI Service</p> 
      <br> <img src="{{asset('assets/images/help/13.png')}}" width="50%"/><br><br>   
      <p>2.12 - Selecione a opção Permissões delegadas</p> 
      <br> <img src="{{asset('assets/images/help/14.png')}}" width="40%"/><br><br>    
      <p>2.13 - Clicar em Expandir Tudo e após isso Marcar TODAS as opcões disponíveis
      <br>Após marcar, Clicar em Adicionar Permissões (Role a tela para baixo)</p> 
      <br> <img src="{{asset('assets/images/help/15.png')}}" width="40%"/>
      <img src="{{asset('assets/images/help/16.png')}}" width="40%"/>
      <br><br>    
      <p>2.14 - Clicar em Conceder Consetimento do Administrador</p> 
      <p>(Só será possível conceder se o usuário for um administrador - Passo 1 desse Tutorial)</p>
      <br> <img src="{{asset('assets/images/help/17.png')}}" width="60%"/><br><br>   
      <p>2.15 - Se tudo ocorreu bem, deverá ficar com as permissões concedidas e já podemos configurar a conta no Portal</p> 
      <br> <img src="{{asset('assets/images/help/18.png')}}" width="60%"/><br><br>           
      </b>
    </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
         3 - Desabilitar Autenticação 2 Fatores Portal Azure
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
       <p><b>3.1 - Dentro do <a href="https://portal.azure.com" target="_blank">Portal Azure</a> </p>
       <p>3.2 - Acessar o menu e ir em Azure Active Directory</p> 
       <br> <img src="{{asset('assets/images/help/4.png')}}" width="40%"/><br><br>   
       <p>3.3 - No menu clique em Propriedades</p> 
       <br> <img src="{{asset('assets/images/help/20.png')}}" width="40%"/><br><br>
       <p>3.4 - A Direita, role a tela para baixo e clique no link Gerenciar Padrões de Segurança</p> 
       <br> <img src="{{asset('assets/images/help/21.png')}}" width="40%"/><br><br>
       <p>3.5 - Selecione a opção Não e clique em Salvar</p> 
       <br> <img src="{{asset('assets/images/help/22.png')}}" width="40%"/><br><br>
      </div>
    </div>
    </b>
  </div>
  <div class="card">
    <div class="card-header" id="headingFour">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
         4 - Configuração da Conta no Portal
        </button>
      </h2>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
      <div class="card-body">
       <p>4.1 - Acesse o menu <a href="{{route('parceiro.powerbi')}}"><i class="link-icon" data-feather="pocket"></i> <span class="link-title">Power BI</span></a> aqui da Plataforma</p>
       <p>4.2 - Clique no botão Cadastrar Dados Power BI</p> 
       <br> <img src="{{asset('assets/images/help/19.png')}}" width="80%"/><br><br>  
       <p>
        <b>4.3 - Preencher os dados com:</b><br>
        4.31 - Seu usuário do Power BI PRO<br>
        4.32 - Sua Senha do Power BI PRO<br>
        4.33 - Seu Client ID que você pegou no <b>item 2.6 do Passo 2</b> que é o ID DO APLICATIVO<br>
        4.34 - Seu Client Secret que você pegou no <b>item 2.9 do Passo 2</b><br>
        4.35 - Seu Diretório ID que você pegou no <b>item 2.6 do Passo 2</b> que é o ID DO DIRETÓRIO(LOCATÁRIO)<br>
        <br>
         <b>Para testar o funcionamento, basta ir no menu Relatórios e adicionar um novo Relatório</b>
       </p>
      </div>
    </div>
  </div>
</div>
@endsection