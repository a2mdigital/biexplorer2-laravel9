<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantParceiro extends Model
{
    protected $table = 'tenants';
    protected $fillable = [
        'nome',
        'conta_powerbi',
        'limite_usuarios',
        'utiliza_filtro',
        'filtro_tabela',
        'filtro_coluna',
        'filtro_valor',
        'utiliza_rls',
        'regra_rls',
        'username_rls',
        'valor_usuario',
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
        return $this->hasMany(User::class, 'tenant_id', 'id');
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
