<?php

namespace App\Models;


use App\Tenant\TenantTraitUsers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTenant extends Model
{
    use HasFactory;
    use TenantTraitUsers;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'troca_senha',
        'is_admin',
        'session_id',
        'utiliza_filtro',
        'filtro_tabela',
        'filtro_coluna',
        'filtro_valor',
        'departamento_id',
        'utiliza_rls',
        'regra_rls',
        'username_rls',
        'tenant_id',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function tenantParceiro()
    {
        return $this->belongsTo(TenantParceiro::class);
    }
}
