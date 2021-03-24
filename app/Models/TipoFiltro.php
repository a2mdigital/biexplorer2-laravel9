<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoFiltro extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['nome'];

    protected $searchableFields = ['*'];

    protected $table = 'tipo_filtros';
}
