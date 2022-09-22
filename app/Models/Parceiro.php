<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Parceiro extends Authenticatable implements JWTSubject
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
        'tamanho_imagem_login',
        'fundo_imagem_login',
        'menu_color',
        'menu_contraido',
        'password',
        'troca_senha',
        'inativado'
    ];


    protected $searchableFields = ['*'];

    protected $hidden = ['password'];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

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
