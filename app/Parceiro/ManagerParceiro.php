<?php 

namespace App\Parceiro;

use App\Models\Tenant;
use App\Models\TenantParceiro;
use Illuminate\Support\Facades\Auth;

class ManagerParceiro{
    public function getParceiroIdentify(){
        
        if(Auth::guard('parceiro')->check()){
            return auth()->user()->id;
        }
        if(Auth::guard('web')->check()){
           $tenant_id = auth()->user()->tenant_id;
           $tenant = TenantParceiro::find($tenant_id); 
           return $tenant->parceiro_id;
           //return auth()->user()->tenant->id;
        }
    }
}