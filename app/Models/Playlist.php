<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Tenant\TenantTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Playlist extends Model
{
    use HasFactory;
    use Searchable;
    use TenantTrait;
    
    protected $fillable = ['nome', 'tempo_atualizacao', 'tenant_id', 'user_id'];

    protected $searchableFields = ['*'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function playlistItens()
    {
        return $this->hasMany(PlaylistIten::class);
    }
}
