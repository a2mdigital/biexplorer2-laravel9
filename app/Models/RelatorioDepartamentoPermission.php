<?php

namespace App\Models;

use App\User\DepartamentoUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioDepartamentoPermission extends Model
{
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

    public static function boot(){
        
        parent::boot();
        /*PEGAR ID DO USUÁRIO LOGADO*/
       static::addGlobalScope(new DepartamentoUserScope);
       
    }
}
