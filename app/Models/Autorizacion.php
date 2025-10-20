<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    protected $table = 'autorizaciones';
    protected $primaryKey = 'id_autorizacion';
    public $timestamps = false;
    protected $fillable = [
        'id_solicitud',
        'autorizado_por',
        'observaciones',
        'fecha_autorizacion',
        'estado',
    ];
        // Relaciones
        public function solicitud()
        {
            return $this->belongsTo(Solicitud::class, 'id_solicitud');
        }

        public function usuario()
        {
            return $this->belongsTo(Usuario::class, 'autorizado_por');
        }
}