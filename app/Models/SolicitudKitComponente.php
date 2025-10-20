<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudKitComponente extends Model
{
    protected $table = 'solicitud_kit_componentes';
    public $timestamps = false;
    protected $fillable = ['id_solicitud_kit','id_componente','cantidad_solicitada','cantidad_entregada'];

    public function componente()
    {
        return $this->belongsTo(KitComponente::class, 'id_componente');
    }

    public function solicitud()
    {
        return $this->belongsTo(SolicitudKit::class, 'id_solicitud_kit');
    }
}
