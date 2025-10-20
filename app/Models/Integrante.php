<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integrante extends Model
{
    protected $table = 'integrantes';
    protected $primaryKey = 'id_integrante';
    public $timestamps = false;
    protected $fillable = [
        'id_solicitud',
        'codigo_estudiante',
        'nombre_estudiante',
    ];
        // Relaciones
        public function solicitud()
        {
            return $this->belongsTo(Solicitud::class, 'id_solicitud');
        }
}