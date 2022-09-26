<?php

namespace App\Models;


use App\Parceiro\ParceiroTrait;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PowerBiParceiro extends Model
{
    use HasFactory;
    use Searchable;
    use ParceiroTrait;

    protected $fillable = [
        'user_powerbi',
        'password_powerbi',
        'client_id',
        'client_secret',
        'diretorio_id',
        'parceiro_id',
        'bearer_token_api_a2m',
        'data_expira_token'
    ];

    protected $searchableFields = ['*'];

    protected $table = 'power_bi_parceiros';

 
    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class);
    }
}
