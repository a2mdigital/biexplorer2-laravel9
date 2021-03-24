<?php

namespace App\Models;


use App\Models\Scopes\Searchable;
use App\Parceiro\ParceiroTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrupoRelatorioParceiro extends Model
{
    use HasFactory;
    use Searchable;
    use ParceiroTrait;
    
    protected $fillable = ['nome', 'cor', 'parceiro_id'];

    protected $searchableFields = ['*'];

    protected $table = 'grupo_relatorio_parceiros';

  
    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class);
    }

    public function subGrupoRelatorios()
    {
        return $this->hasMany(SubGrupoRelatorio::class, 'grp_rel_parceiro_id');
    }
}
