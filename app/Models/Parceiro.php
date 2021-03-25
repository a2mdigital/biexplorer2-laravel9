<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Parceiro extends Authenticatable
{
    use HasFactory;
    use Searchable;
    protected $guard = 'parceiro';

    protected $fillable = [
        'email',
        'name',
        'is_admin',
        'rota_login_logout',
        'subdomain',
        'imagem_login',
        'menu_color',
        'menu_contraido',
        'password',
        'troca_senha',
    ];


    protected $searchableFields = ['*'];

    protected $hidden = ['password'];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function powerBiParceiros()
    {
        return $this->hasMany(PowerBiParceiro::class);
    }

    public function grupoRelatorioParceiros()
    {
        return $this->hasMany(GrupoRelatorioParceiro::class);
    }

    public function grpAcessParcs()
    {
        return $this->belongsToMany(GrpAcessParc::class, 'grp_acess_parceiro');
    }

}
