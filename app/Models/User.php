<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasFactory;
    use Searchable;
    protected $guard = 'web';
      
  

    protected $fillable = [
        'name',
        'email',
        'password',
        'troca_senha',
        'is_admin',
        'session_id',
        'menu_color',
        'menu_contraido',
        'utiliza_filtro',
        'filtro_tabela',
        'filtro_coluna',
        'filtro_valor',
        'departamento_id',
        'utiliza_rls',
        'regra_rls',
        'username_rls',
        'tenant_id',
        'ultimo_login',
    ];

   
    protected $searchableFields = ['*'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
    
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function tenantParceiro()
    {
        return $this->belongsTo(TenantParceiro::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function relatorios()
    {
        return $this->belongsToMany(Relatorio::class);
    }

    public function grupoAcessos()
    {
        return $this->belongsToMany(GrupoAcesso::class);
    }

  
}
