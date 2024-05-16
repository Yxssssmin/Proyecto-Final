<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombreReserva' => 'required',
            'tipoComida' => 'required',
            'ubicacion' => 'required',
            'numeroPersonas' => 'required|integer|min:1',
            'numeroContacto' => 'required',
            'fechaReserva' => 'required|date',
            'horaReserva' => 'required',
        ]);

        // Crear una nueva reserva
        $reserva = new Reserva();
        $reserva->nombre_reserva = $request->nombreReserva;
        $reserva->tipo_comida = $request->tipoComida;
        $reserva->ciudad = $request->ubicacion;
        $reserva->numero_personas = $request->numeroPersonas;
        $reserva->numero_contacto = $request->numeroContacto;
        $reserva->fecha_reserva = $request->fechaReserva;
        $reserva->hora_reserva = $request->horaReserva;
        
        $reserva->save();

        return response()->json(['message' => 'Reserva creada exitosamente'], 201);
    }

   
}
