<div class="row">
  @foreach($grupos as $grupo)
  <div class="col-md-5 col-sm-5 col-xs-3">
    <div class="card text-white" style="margin-left:4px; margin-bottom:6px; background-color: {{$grupo->cor}};">
      <div class="card-header">
      <div class="row">
          <div class="col-md-8 col-sm-8 col-xs-8">
          {{$grupo->nome}}
          </div>
          <div style="display: flex; justify-content:flex-start" class="col-md-4 col-sm-4 col-xs-4 float-right" id="link-card">
          <a href="#" class="link-card" data-id="{{$grupo->id}}" data-nome="{{$grupo->nome}}" data-cor="{{$grupo->cor}}" data-toggle="modal" data-target="#grupoModalEdit"><i data-feather="edit" class="icon-card-grupo" data-toggle="tooltip"  data-placement="top" title="Editar"></i></a>
          <form action="{{route('parceiro.gruposrelatorio.excluir', $grupo->id)}}" method="POST">
                        @csrf
                        {{ method_field('DELETE') }}
                        <button type="submit"  data-toggle="tooltip" data-placement="top" title="Excluir"  onclick="return confirm('Excluir o Grupo apagará todos os subgrupos e relatórios dentro dele, Excluir?')" class="btn btn-excluir-card">
                        <i data-feather="trash"></i>
                          </button>
          </form>
          </div>
      </div>
      </div>
      <div class="card-body">
      <center><a href={{route('parceiro.subgrupos.relatorios', $grupo->id)}} class="btn btn-outline-light">Acessar</a></center>
      </div>
  </div>   
  </div>
  @endforeach
</div>