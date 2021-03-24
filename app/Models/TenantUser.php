<?php

namespace App\Models;

use App\Tenant\TenantUserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantUser extends Model
{
    use HasFactory;
   
    protected $table = 'tenants';
    protected $fillable = [
        'nome',
        'limite_usuarios',
        'utiliza_filtro',
        'filtro_tabela',
        'filtro_coluna',
        'filtro_valor',
        'valor_usuario',
        'utiliza_rls',
        'regra_rls',
        'username_rls',
        'valor_usuario',
        'email_administrador',
        'parceiro_id',
    ];

    public static function boot(){
        
        parent::boot();
        /*PEGAR TENANT ID DO USUÁRIO LOGADO*/
       static::addGlobalScope(new TenantUserScope);
       
    }
   

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
