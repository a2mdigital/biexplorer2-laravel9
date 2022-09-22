<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Parceiro\ParceiroTraitTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Tenant extends Model
{

    use HasFactory;
    use Searchable;
    use ParceiroTraitTenant;

    protected $fillable = [
        'nome',
        'limite_usuarios',
        'utiliza_filtro',
        'filtro_tabela',
        'filtro_coluna',
        'filtro_valor',
        'utiliza_rls',
        'regra_rls',
        'username_rls',
        'valor_usuario',
        'email_administrador',
        'parceiro_id',
    ];

  
   

    protected $searchableFields = ['*'];

    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class);
    }

    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function usersTenant()
    {
        return $this->hasMany(UserTenant::class);
    }

    public function powerBiEmpresas()
    {
        return $this->hasMany(PowerBiEmpresa::class);
    }

    public function subGrupoRelatorio()
    {
        return $this->belongsTo(SubGrupoRelatorio::class);
    }

    public function relatorios()
    {
        return $this->belongsToMany(Relatorio::class,'relatorio_tenant', 'relatorio_id', 'tenant_id');
    }
}
