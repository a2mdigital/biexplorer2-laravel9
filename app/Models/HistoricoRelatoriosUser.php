<?php

namespace App\Models;

use App\User\HistoricoRelatorioUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoRelatoriosUser extends Model
{
    use HistoricoRelatorioUserTrait;
    protected $table = 'historico_relatorio_users';
    protected $fillable = [
        'user_id',
        'relatorio_id',
        'tenant_id',
        'departamento_id',
        'favorito',
        'ultima_hora_acessada',
        'qtd_acessos'
    ];

 public function relatorios(){
     return $this->belongsTo(Relatorio::class, 'relatorio_id');
 }
  
}
