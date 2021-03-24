<?php

namespace App\Models;

use App\Tenant\TenantTrait;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlaylistIten extends Model
{
    use HasFactory;
    use Searchable;
    use TenantTrait;
    
    protected $fillable = [
        'ordem',
        'navega_paginas',
        'playlist_id',
        'relatorio_id',
        'tenant_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'playlist_itens';

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function relatorio()
    {
        return $this->belongsTo(Relatorio::class);
    }
}
