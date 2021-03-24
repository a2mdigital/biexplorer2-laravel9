<?php

namespace App\Models;


use App\User\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RelatorioUserPermission extends Model
{
    protected $table = 'relatorio_user';
    protected $fillable = [
        'user_id',
        'relatorio_id',
        'tenant_id',
        'favorito',
        'ultima_hora_acessada',
        'qtd_acessos',
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
       static::addGlobalScope(new UserScope);
       
    }
}
