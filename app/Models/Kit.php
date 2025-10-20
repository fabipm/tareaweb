<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kit extends Model
{
    protected $table = 'kits';
    protected $primaryKey = 'id_kit';
    public $timestamps = true;
    protected $fillable = [
        'nombre_kit', 'codigo_kit', 'descripcion', 'stock_total', 'stock_disponible', 'disponible'
    ];

    public function componentes()
    {
        return $this->hasMany(KitComponente::class, 'id_kit');
    }
}
