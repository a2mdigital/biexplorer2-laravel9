<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Relatorio;
use App\Tenant\ManagerTenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\RelatorioUserPermission;
use Illuminate\Support\ServiceProvider;
use App\Models\RelatorioDepartamentoPermission;
use App\Models\RelatorioTenant;
use App\Models\SubGrupoRelatorio;

class RelatorioPermission extends ServiceProvider
{ 
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
       
      
        Gate::define('listar-grupo-relatorio-admin', function($user = null, $grupo){
       
          $tenant = app(ManagerTenant::class)->getTenantIdentify(); 
          return (bool) DB::table('relatorio_tenant')
            ->join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
            ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
            ->select('relatorios.id as id', 'relatorios.nome as nome')
            ->where('tenants.id', '=', $tenant)
            ->where('relatorios.subgrupo_relatorio_id', '=', $grupo->id)->count();
            
        });

        Gate::define('permissao-relatorio-admin', function($user = null, $relatorio){
          
            //$tenant = app(ManagerTenant::class)->getTenantIdentify(); 
            return (bool)  DB::table('relatorio_tenant')
            ->join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
            ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
            ->select('tenants.nome', 'tenants.id as tenant_id', 'relatorios.id as relatorio_id')
            ->where('relatorio_tenant.relatorio_id', '=', $relatorio->id)->count();
              
          });
      
          Gate::define('permissao-visualizar-relatorio-admin', function($user = null, $relatorio){
          
            return (bool) RelatorioTenant::where('relatorio_id', $relatorio->id)->count();
              
          });

        //VERIFICA SE O USUÁRIO LOGADO TEM ACESSO AOS GRUPOS DOS RELATÓRIOS PERMITIDOS
        Gate::define('listar-grupo-relatorio-user', function(User $user, $grupo){

            //buscar relatórios do usuário nos departamentos e no usuário
            $relatorios_user = RelatorioUserPermission::select('relatorio_id')->get();
            $relatorios_departamento = RelatorioDepartamentoPermission::select('relatorio_id')->get();
          
            $subGruposPermission = Relatorio:: select('subgrupo_relatorio_id')
            ->where(function($query) use ($grupo){
                $query->where('subgrupo_relatorio_id', $grupo->id);
            })
            ->where(function($query) use ($relatorios_user, $relatorios_departamento){
                $query->orWhereIn('id', $relatorios_user);
                $query->orWhereIn('id', $relatorios_departamento);
            })->get();

            return (bool) SubGrupoRelatorio::whereIn('id', $subGruposPermission)->count();
           
        });

        //VERIFICA SE O USUÁRIO LOGADO TEM ACESSO AO RELATÓRIO
        Gate::define('visualizar-relatorio-user', function(User $user, $grupo, $relatorio){
            
            $relatorioExisteNoGrupo = (bool) Relatorio::where('id', $relatorio)->where('subgrupo_relatorio_id', $grupo)->count();
            if($relatorioExisteNoGrupo){
                $relatorios_user =  (bool) RelatorioUserPermission::where('tenant_id', $user->tenant->id)->where('relatorio_id', $relatorio)->count();
                $relatorios_departamento = (bool) RelatorioDepartamentoPermission::where('tenant_id', $user->tenant->id)->where('relatorio_id', $relatorio)->count();

                if($relatorios_user || $relatorios_departamento){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        });
      
    }
}
