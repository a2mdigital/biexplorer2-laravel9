<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Searchable;
use App\Tenant\TenantTrait;

class DepartamentoTenant extends Model
{
    use HasFactory;
    use Searchable;
    use TenantTrait;

    protected $table = "departamentos";
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
        return $this->hasMany(UserTenant::class, 'departamento_id');
    }

    public function relatorios()
    {
        return $this->belongsToMany(Relatorio::class);
    }
}
