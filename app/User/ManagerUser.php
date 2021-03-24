<?php 

namespace App\User;



class ManagerUser{
    public function getUserIdentify(){
    
      return auth()->user()->id; 
    
    }
}