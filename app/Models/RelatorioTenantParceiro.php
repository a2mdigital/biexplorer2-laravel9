<?php

namespace App\Models;


use App\Parceiro\ParceiroTraitRelatorioTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioTenantParceiro extends Model
{

    use ParceiroTraitRelatorioTenant;

    protected $table = 'relatorio_tenant';
    protected $fillable = [
        'relatorio_id',
        'tenant_id',
    ];


    protected $primaryKey = null;
    public $incrementing = false;
   // public $timestamps = false;
}
