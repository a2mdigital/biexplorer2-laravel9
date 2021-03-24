<?php 

namespace App\User;



class ManagerDepartamentoUser{
    public function getDepartamentoIdentify(){
    
     // return auth()->user()->departamento->id; 
     return auth()->user()->departamento_id; 
    }
}