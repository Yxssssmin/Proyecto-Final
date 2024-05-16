<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function getCities() {

        // Obtener todas las ciudades únicas de la columna 'City' en la tabla 'restaurantes'
        $cities = DB::table('restaurantes')->distinct()->pluck('City')->toArray();
        
        // Devolver las ciudades como respuesta JSON
        return response()->json($cities);
    }

    public function obtenerTiposDeComida()
    {
        // Obtener la lista de tipos de comida desde la base de datos
        $tiposDeComida = DB::table('restaurantes')->distinct()->pluck('Cuisine_Style')->flatMap(function ($tipo) {
            return explode(', ', str_replace(['[', ']', '\'', '"'], '', $tipo));
        })->unique()->values()->all();

        return response()->json($tiposDeComida);
    }
}
