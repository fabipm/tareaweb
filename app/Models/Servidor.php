<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
    protected $table = 'servidores';
    protected $primaryKey = 'id_servidor';
    public $timestamps = false;
    protected $fillable = [
        'nombre_servidor',
        'serie_servidor',
        'tipo_servidor',
        'caracteristicas',
        'disponible',
    ];
        // Relaciones
        public function solicitudes()
        {
            return $this->hasMany(Solicitud::class, 'id_servidor');
        }
}