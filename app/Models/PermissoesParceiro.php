<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissoesParceiro extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['nome_permissao', 'detalhe_permissao'];

    protected $searchableFields = ['*'];

    protected $table = 'permissoes_parceiros';

    public function grpAcessParcs()
    {
        return $this->belongsToMany(GrpAcessParc::class, 'grp_acess_perm_parc');
    }
}
