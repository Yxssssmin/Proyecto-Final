<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RestaurantController extends Controller
{
    public function getCities() {
        $cities = DB::table('restaurantes')->distinct()->pluck('City')->toArray();
        return response()->json($cities);
    }

    public function obtenerTiposDeComida(Request $request)
    {
        $city = $request->input('city');
        $tiposDeComida = DB::table('restaurantes')
            ->where('City', $city)
            ->distinct()
            ->pluck('Cuisine_Style')
            ->flatMap(function ($tipo) {
                return explode(', ', str_replace(['[', ']', '\'', '"'], '', $tipo));
            })
            ->unique()
            ->values()
            ->all();

        return response()->json($tiposDeComida);
    }

    public function filtrar(Request $request)
    {
        $tipoComida = $request->query('tipoComida');
        $ubicacion = $request->query('ubicacion');

        $query = DB::table('restaurantes');

        if ($tipoComida) {
            $query->where('Cuisine_Style', $tipoComida);
        }

        if ($ubicacion) {
            $query->where('City', $ubicacion);
        }

        $restaurants = $query->get()->map(function ($restaurant) {
            $restaurant->Reviews = $this->formatReviews($restaurant->Reviews);
            return $restaurant;
        });

        return response()->json($restaurants);
    }

    public function mostrarVistaFiltrada(Request $request) {
        $tipoComida = $request->query('tipoComida');
        $ubicacion = $request->query('ubicacion');

        $restaurantes = DB::table('restaurantes')
            ->select('Id', 'Name', 'City', 'Cuisine_Style', 'Ranking', 'Rating', 'Price_Range', 'Number_of_Reviews', 'Reviews', 'URL_TA')
            ->when($tipoComida, function ($query, $tipoComida) {
                return $query->where('Cuisine_Style', 'like', '%' . $tipoComida . '%');
            })
            ->when($ubicacion, function ($query, $ubicacion) {
                return $query->where('City', $ubicacion);
            })
            ->get()
            ->map(function ($restaurant) {
                $restaurant->Reviews = $this->formatReviews($restaurant->Reviews);
                return $restaurant;
            });

        return Inertia::render('Restaurantes', [
            'restaurantes' => $restaurantes,
            'tipoComida' => $tipoComida,
            'ubicacion' => $ubicacion,
        ]);
    }

    public function getRestaurantesPorCiudad() {
        $restaurantesPorCiudad = DB::table('restaurantes')
            ->select('City', DB::raw('count(*) as total_restaurantes'))
            ->groupBy('City')
            ->get();

        return response()->json($restaurantesPorCiudad);
    }

    private function formatReviews($reviews)
    {
        $reviewsArray = json_decode($reviews, true);
        
        // Si json_decode falla, $reviewsArray será null
        if (is_null($reviewsArray)) {
            return [];
        }

        // Verificar que la estructura de $reviewsArray sea la esperada
        if (is_array($reviewsArray) && count($reviewsArray) == 2 && is_array($reviewsArray[0]) && is_array($reviewsArray[1])) {
            $comments = $reviewsArray[0];
            $dates = $reviewsArray[1];
            $formattedReviews = [];
            foreach ($comments as $index => $comment) {
                $formattedReviews[] = [
                    'content' => $comment,
                    'date' => $dates[$index] ?? null,
                ];
            }
            return $formattedReviews;
        }

        // Si la estructura no es la esperada, devolver un array vacío
        return [];
    }
}
