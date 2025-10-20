<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitComponente extends Model
{
    protected $table = 'kit_componentes';
    protected $primaryKey = 'id_componente';
    public $timestamps = false;
    protected $fillable = ['id_kit','nombre_componente','cantidad_en_kit','unidad','descripcion','stock'];

    public function kit()
    {
        return $this->belongsTo(Kit::class, 'id_kit');
    }
}
