@extends('layout.parceiros.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Power Bi</a></li>
    <li class="breadcrumb-item active" aria-current="page">Conta Power Bi</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      
        <p class="card-description"></p>
        <div class="table-responsive">
          <table id="powerbiTable" class="table">
            <thead>
              <tr>
                <th>Usuário</th>
                <th>Client ID</th>
                <th>Diretorio ID</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
            @foreach($powerbi as $pb)
            <tr>
            <td>
            {{$pb->user_powerbi}}
            </td>
            <td>
            {{$pb->client_id}}
            </td>
            <td>
            {{$pb->diretorio_id}}
            </td>
            <td>
                <a href="{{route('parceiro.powerbi.editar', $pb->id)}}" class="btn btn-primary btn-sm">Editar</a>
            </td>
            </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
<div class="col-md-3 btnCadastrar">
        <a href="{{route('parceiro.powerbi.cadastrar')}}" class="btn btn-primary btn-icon-text">
                <i class="btn-icon-prepend" data-feather="check-square"></i>
                Cadastrar Dados Power Bi
        </a>
</div>
<div class="col-md-3">
        <button onclick="testarConexao()" class="btn btn-primary btn-icon-text">
                <i class="btn-icon-prepend" data-feather="check-square"></i>
                Testar Conexão
        </button>
</div>
</div>
<br>
<div class="row" id="error">

</div>
@if (count($powerbi) > 0)
<div class="row">
  <div class="col-md-12">
      <div class="mb-3">
              <label for="exampleFormControlTextarea1" class="form-label">Token API</label>
              <textarea readonly class="form-control" id="bearer_token" rows="5">Bearer {{$powerbi[0]['bearer_token_api_a2m']}}</textarea>
        </div>
  </div>        
</div>
<div class="row">
  <div class="col-md-3">
      <div class="mb-3">
              <label for="exampleFormControlTextarea1" class="form-label">Expira em:</label>
              <input type="text" id="expira_token_em" readonly value="{{ date('d-m-Y', strtotime($powerbi[0]['data_expira_token']))}}"></input>
        </div>
  </div>        
</div>
<div class="row">
<div class="col-md-3">
    <button type="button" data-toggle="modal" data-target="#modalApi" class="btn btn-primary btn-icon-text">
      <i class="btn-icon-prepend" data-feather="check-square"></i>
      Gerar Token API
    </button>
</div>
</div>
@endif
<!-- MODAL LOGIN -->
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
  <!-- MODAL CADASTRO -->
      <div id="modalApi" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="modalTitle2" class="modal-title">Digite sua senha novamente</h5>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">Fechar</span></button>
          </div>
          <div id="modalBody2" class="modal-body">
          <form id="form-gerartoken">
            <input type="hidden" name="parceiro_id" id="parceiro_id" value="{{$user->id}}">
            @csrf
              <div class="form-group {{$errors->has('email') ? 'has-danger' : ''}}">
                <label for="email">E-mail</label>
                <input type="text" value="{{$user->email}}" readonly class="form-control {{$errors->has('email') ? 'form-control-danger' : ''}}" id="email" name="email" autofocus placeholder="Login">
              
              </div>
              <div class="form-group">
               <label for="password">Senha</label>
                    <input type="password" name="password" id="password" placeholder="Senha" class="form-control" />
                    <div id="mostrar-erro-senha">
                    <label id="password-error" class="error mt-2 text-danger" for="password">
                      Senha Inválida
                    </label>
                    </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
             <button  class="btn btn-primary">Fazer Login</button>
          </div>
          </form>
        </div>
      </div>
    </div>
</div>
</div>
@endsection
@push('custom-scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">

 function testarConexao(){

    $.ajax({
                              method: "GET",
                              url:'{{route("parceiro.powerbi.testarconexao")}}',
                              success: function(data) {

                               if(data.resposta == 'ok'){
                                Swal.fire(
                                'OK',
                                'Conexão testada com sucesso',
                                'success'
                              );
                           
                               }else{
                                Swal.fire(
                                'Erro',
                                'Não foi possível realizar a conexão, verifique as configurações!',
                                'error'
                              );
                                $('#error').text(data.msg);
                               }
                            
                              }
    });
  }    

$(document).ready(function() {

    $('#mostrar-erro-senha').hide();

    var cadastro = $('#powerbiTable >tbody >tr').length;
    if(cadastro > 0){
      $('.btnCadastrar').hide(); 
    }else{
      $('.btnCadastrar').show(); 
    }

    //FORM PARA GERAR TOKEN
    $("#form-gerartoken").submit(function (event) {
        var formData = {
          "_token": "{{ csrf_token() }}",
          parceiro_id: $("#parceiro_id").val(),
          email: $("#email").val(),
          password: $("#password").val()
        };
         
        $.ajax({
          method: "POST",
          url:'{{route("parceiro.api.gerartoken")}}',
          data: formData,
          encode: true,
        }).done(function (data) {
          if(data.resposta == 'ok'){
            console.log(data);
            $('#modalApi').modal('hide');
            $('#mostrar-erro-senha').hide();
            $('#password').val('');
            $('#bearer_token').text('Bearer ' + data.token);
            $('#expira_token_em').val(data.expira_em);
          }else{
            $('#mostrar-erro-senha').show();
          }
          
        });
        

        event.preventDefault();
      });
 

});
</script>
@endpush
