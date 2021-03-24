<?php 

namespace App\Parceiro;

use Illuminate\Database\Eloquent\Model;

class ParceiroObserver
{
    public function creating(Model $model){

        $parceiro = app(ManagerParceiro::class)->getParceiroIdentify();
        $model->setAttribute('parceiro_id', $parceiro);
    }
}