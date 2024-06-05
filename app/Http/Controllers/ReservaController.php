<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReservaController extends Controller
{

    //formulario para la reserva
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombreReserva' => 'required',
            'tipoComida' => 'required',
            'ubicacion' => 'required',
            'numeroPersonas' => 'required|integer|min:1|max:5',
            'numeroContacto' => 'required',
            'fechaReserva' => 'required|date',
            'horaReserva' => 'required|date_format:H:i',
        ]);


        $fechaHoraReserva = $request->fechaReserva . ' ' . $request->horaReserva;

        // Crear una nueva reserva
        $reserva = new Reserva();
        $reserva->id_usuario = Auth::id();
        $reserva->nombre_reserva = $request->nombreReserva;
        $reserva->tipo_comida = $request->tipoComida;
        $reserva->ciudad = $request->ubicacion;
        $reserva->numero_personas = $request->numeroPersonas;
        $reserva->numero_contacto = $request->numeroContacto;
        $reserva->fecha_reserva = $fechaHoraReserva; // Guardar como datetime
        
        $reserva->save();

        return response()->json(['message' => 'Reserva creada exitosamente'], 201);
    }

    public function getReservas() {
        
        $reservas = DB::table('reservas')->get();
        return Inertia::render('ReservasList', [
            'reservas' => $reservas,
        ]);
    }
    
    public function getAllReservas() {
        // Obtener el ID del usuario autenticado
        $userId = Auth::id();
    
        // Obtener las reservas del usuario autenticado
        $reservas = DB::table('reservas')->where('id_usuario', $userId)->get();
    
        return response()->json($reservas);
       
    }


    
    public function getReservasGrafica() {

        // Realiza la consulta para contar las reservas por usuario
        $reservasPorUsuario = DB::table('reservas')
            ->select('id_usuario', DB::raw('count(*) as total_reservas'))
            ->groupBy('id_usuario')
            ->get();

        // Devuelve el resultado en formato JSON
        return response()->json($reservasPorUsuario);
    }

   
}
