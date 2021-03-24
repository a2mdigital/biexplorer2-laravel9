<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrpAcessParc extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['nome_grupo_acesso', 'detalhe_grupo_acesso'];

    protected $searchableFields = ['*'];

    protected $table = 'grp_acess_parcs';

    public function permissoesParceiros()
    {
        return $this->belongsToMany(
            PermissoesParceiro::class,
            'grp_acess_perm_parc'
        );
    }

    public function parceiros()
    {
        return $this->belongsToMany(Parceiro::class, 'grp_acess_parceiro');
    }
}
