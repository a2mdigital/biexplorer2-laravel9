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
    var cadastro = $('#powerbiTable >tbody >tr').length;
    if(cadastro > 0){
      $('.btnCadastrar').hide(); 
    }else{
      $('.btnCadastrar').show(); 
    }

 

});
</script>
@endpush
