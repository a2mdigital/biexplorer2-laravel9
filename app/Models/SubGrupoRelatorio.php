<?php

namespace App\Models;


use App\Models\Scopes\Searchable;
use App\Parceiro\ParceiroTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubGrupoRelatorio extends Model
{
    use HasFactory;
    use Searchable;
    use ParceiroTrait;

    protected $fillable = ['nome', 'cor', 'grp_rel_parceiro_id'];

    protected $searchableFields = ['*'];

    protected $table = 'sub_grupo_relatorios';

   

    public function grupoRelatorioParceiro()
    {
        return $this->belongsTo(
            GrupoRelatorioParceiro::class,
            'grp_rel_parceiro_id'
        );
    }

    public function relatorios()
    {
        return $this->hasMany(Relatorio::class);
    }
}
