<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Parceiro\ParceiroTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Relatorio extends Model
{
    use HasFactory;
    use Searchable;
    use ParceiroTrait;

    protected $fillable = [
        'nome',
        'descricao',
        'tipo',
        'utiliza_filtro_rls',
        'ignora_filtro_rls',
        'nivel_filtro_rls',
        'filtro_lateral',
        'report_id',
        'workspace_id',
        'dataset_id',
        'subgrupo_relatorio_id',
        'parceiro_id'
    ];
 
    protected $searchableFields = ['*'];

    public function playlistItens()
    {
        return $this->hasMany(PlaylistIten::class);
    }

    public function subGrupoRelatorio()
    {
        return $this->belongsTo(SubGrupoRelatorio::class);
    }


    public function tenants()
    {
        return $this->belongsToMany(Tenant::class);
    }

    public function departamentos()
    {
        return $this->belongsToMany(Departamento::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    //pertence a um ou mais de um tenant
    public function RelatorioTenant(){
        return $this->belongsToMany(RelatorioTenant::class, 'relatorio_tenant', 'relatorio_id', 'tenant_id');
    }

    public function historico(){
        return $this->hasMany(HistoricoRelatoriosUser::class);
    }
   

}
