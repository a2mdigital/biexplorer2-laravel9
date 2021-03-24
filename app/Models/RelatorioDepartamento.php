<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\TenantTraitDepartamentos;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RelatorioDepartamento extends Model
{
    use TenantTraitDepartamentos;
    protected $table = 'departamento_relatorio';
    protected $fillable = [
        'departamento_id',
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
