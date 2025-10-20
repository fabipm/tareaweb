<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';
    protected $primaryKey = 'id_solicitud';
    public $timestamps = false;
    protected $fillable = [
        'id_usuario',
        'id_servidor',
        'semestre_academico',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'incluir_monitor',
        'incluir_teclado',
        'incluir_mouse',
        'codigo_responsable',
        'nombre_responsable',
        'estado',
        'fecha_registro',
    ];
        // Relaciones
        public function usuario()
        {
            return $this->belongsTo(Usuario::class, 'id_usuario');
        }

        public function servidor()
        {
            return $this->belongsTo(Servidor::class, 'id_servidor');
        }

        public function integrantes()
        {
            return $this->hasMany(Integrante::class, 'id_solicitud');
        }

        public function autorizaciones()
        {
            return $this->hasMany(Autorizacion::class, 'id_solicitud');
        }
}