<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudKit extends Model
{
    protected $table = 'solicitudes_kit';
    protected $primaryKey = 'id_solicitud_kit';
    // La tabla `solicitudes_kit` ahora tiene `created_at` y `updated_at`.
    // Activamos timestamps de Eloquent para que gestione esas columnas.
    public $timestamps = true;
    protected $fillable = [
        'id_usuario','id_kit','semestre_academico','tema_proyecto','fecha','hora_entrada','hora_salida','codigo_responsable','nombre_responsable','docente_responsable','curso','estado','estado_kit','personal_soporte','observacion','fecha_registro'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function kit()
    {
        return $this->belongsTo(Kit::class, 'id_kit');
    }

    public function componentes()
    {
        return $this->hasMany(SolicitudKitComponente::class, 'id_solicitud_kit');
    }
}
