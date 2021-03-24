<?php

namespace App\Models;

use App\Tenant\TenantScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RelatorioTenant extends Model
{
    protected $table = 'relatorio_tenant';
    protected $fillable = [
        'relatorio_id',
        'tenant_id',
    ];


    protected $primaryKey = null;
    public $incrementing = false;
    //public $timestamps = false;

    public static function boot(){
        
        parent::boot();
        /*PEGAR TENANT ID DO USUÁRIO LOGADO*/
       static::addGlobalScope(new TenantScope);
       
    }

    public function tenant(){
        return $this->belongsToMany(Tenant::class);
    }

    public function relatorio(){
        return $this->belongsToMany(Relatorio::class);
    }

    public function checkPermissions($tenant, $grupo){

        return (bool) DB::table('relatorio_tenant')
        ->join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
        ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
        ->select('relatorios.id as id', 'relatorios.nome as nome')
        ->where('tenants.id', '=', $tenant)
        ->where('relatorios.subgrupo_relatorio_id', '=', $grupo->id)->count();

    }


}
