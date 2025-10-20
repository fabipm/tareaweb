<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    protected $fillable = [
        'codigo_usuario',
        'nombre_completo',
        'correo',
        'clave',
        'rol',
        'estado',
    ];
        // Relaciones
        public function solicitudes()
        {
            return $this->hasMany(Solicitud::class, 'id_usuario');
        }

        public function autorizaciones()
        {
            return $this->hasMany(Autorizacion::class, 'autorizado_por');
        }
}