<?php

namespace App\Http\Controllers\Administradores;

use App\Models\TenantUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\GetTokenPowerBiService;
use App\Services\GetTokenRlsPowerBiService;

class PowerBiControllerAdmin extends Controller
{
    public function getToken(){
        $resposta = GetTokenPowerBiService::getToken();  
        
        if($resposta['resposta'] == 'ok'){
            return ['resposta' => 'ok', 'token' => $resposta['token'], 'expires_in' => $resposta['expires_in']];
           // return $resposta['token'];
        }else{
           return ['resposta' => 'error']; 
           //return $resposta['error'];
        }
    }
}
