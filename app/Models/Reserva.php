<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'id_usuario',
        'nombre_reserva',
        'tipo_comida',
        'ciudad',
        'numero_personas',
        'numero_contacto',
        'codigo_reserva',
        'fecha_reserva',
        'estado_reserva',
    ];

    // Aquí puedes definir relaciones con otros modelos, por ejemplo:
    // public function usuario() {
    //     return $this->belongsTo(User::class, 'id_usuario');
    // }
}
