<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissoesUsuario extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['nome_permissao', 'detalhe_permissao'];

    protected $searchableFields = ['*'];

    protected $table = 'permissao_usuarios';

    public function grupoAcessos()
    {
        return $this->belongsToMany(
            GrupoAcesso::class,
            'grupo_acesso_permissoes_usuario',
            'permi_user_id'
        );
    }
}
