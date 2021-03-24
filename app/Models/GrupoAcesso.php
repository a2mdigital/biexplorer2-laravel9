<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrupoAcesso extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['nome_grupo_acesso', 'detalhe_grupo_acesso'];

    protected $searchableFields = ['*'];

    protected $table = 'grupo_acessos';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissoes()
    {
        return $this->belongsToMany(
            PermissoesUsuario::class,
            'grupo_acesso_permissoes_usuario',
            'permi_user_id'
        );
    }
}
