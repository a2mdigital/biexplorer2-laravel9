<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departamento extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'nome',
        'utiliza_filtro',
        'filtro_tabela',
        'filtro_coluna',
        'filtro_valor',
        'tenant_id',
    ];

    protected $searchableFields = ['*'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function usersTenant()
    {
        return $this->hasMany(UserTenant::class);
    }
    

    public function relatorios()
    {
        return $this->belongsToMany(Relatorio::class);
    }
}
