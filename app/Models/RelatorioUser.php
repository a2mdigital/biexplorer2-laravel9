<?php

namespace App\Models;

//use App\Tenant\TenantScope;

use App\Tenant\TenantTraitUsers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RelatorioUser extends Model
{

    use TenantTraitUsers;
    protected $table = 'relatorio_user';
    protected $fillable = [
        'user_id',
        'relatorio_id',
        'tenant_id',
        'utiliza_filtro',
        'tipo_filtro',
        'filtro_tabela',
        'filtro_coluna',
        'filtro_valor',
        'utiliza_rls',
        'regra_rls',
        'username_rls'
    ];

   
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

   
}
