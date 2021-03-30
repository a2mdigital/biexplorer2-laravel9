<?php 

namespace App\Tenant;



class ManagerTenant{
    public function getTenantIdentify(){
   
      dd(auth()->user());
      return auth()->user()->tenant->id; 
     
    
    }
}