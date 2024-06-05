<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{

    // PARA LA SECCION DE USUARIOS

    //GRAFICO
    public function getUsers()
    {

        $users = DB::table('users')->get();
        return Inertia::render('Users', [
            'users' => $users,
        ]);
    }

    //TABLA USUARIOS
    public function getAllUsers()
    {
        $users = DB::table('users')->get();
        return response()->json($users);
    }


    //Borrar usuario
    public function deleteUser($userId)
    {
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        DB::table('users')->where('id', $userId)->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    //Actualizar usuario
    public function updateUser(Request $request, $id)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            ]);

            // Actualizar el usuario utilizando el Query Builder
            DB::table('users')
                ->where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'updated_at' => now(), // Asegúrate de actualizar la marca de tiempo si tu tabla la usa
                ]);

            return response()->json(['message' => 'Usuario actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el usuario'], 500);
        }
    }

    //TOTAL DE RESERVAS, USUARIOS Y RESTAURANTES
    public function getTotals()
    {
        $totalUsers = DB::table('users')->count();
        $totalRestaurants = DB::table('restaurants')->count();
        $totalBookings = DB::table('reservations')->count();

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalRestaurants' => $totalRestaurants,
            'totalBookings' => $totalBookings
        ]);
    }

    //SECCION DE RESTURANTES

    public function getRestaurants()
    {

        $restaurantes = DB::table('restaurantes')->get();
        return Inertia::render('RestaurantesDashboard', [
            'restaurantes' => $restaurantes,
        ]);
    }

    //TABLA RESTAURANTES
    public function getAllRestaurants()
    {
        $restaurantes = DB::table('restaurantes')->get();
        return response()->json($restaurantes);
    }

    public function deleteRestaurant($restaurantId)
    {
        $restaurant = DB::table('restaurantes')->where('Id', $restaurantId)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurante no encontrado'], 404);
        }

        DB::table('restaurantes')->where('Id', $restaurantId)->delete();

        return response()->json(['message' => 'Restaurante eliminado exitosamente'], 200);
    }



    public function getAllRestaurantswithCity()
    {
        // Selecciona todas las ciudades únicas de la tabla de restaurantes
        $cities = DB::table('restaurantes')->distinct()->pluck('City');

        // Retorna la lista de ciudades como parte de la respuesta JSON
        return response()->json(['cities' => $cities]);
    }


    //RESERVAS
    public function updateReserva(Request $request, $id)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'nombre_reserva' => 'required|string|max:255',
                'numero_personas' => 'required|integer',
                'numero_contacto' => 'required|string|max:20',
                'fecha_reserva' => 'required|date',
            ]);

            // Actualizar la reserva utilizando el Query Builder
            DB::table('reservas')
                ->where('id', $id)
                ->update([
                    'nombre_reserva' => $request->input('nombre_reserva'),
                    'numero_personas' => $request->input('numero_personas'),
                    'numero_contacto' => $request->input('numero_contacto'),
                    'fecha_reserva' => $request->input('fecha_reserva'),
                    'updated_at' => now(), // Asegúrate de actualizar la marca de tiempo si tu tabla la usa
                ]);

            return response()->json(['message' => 'Reserva actualizada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la reserva'], 500);
        }
    }

    public function deleteReserva($reservaId)
{
    $reserva = DB::table('reservas')->where('id', $reservaId)->first();

    if (!$reserva) {
        return response()->json(['message' => 'Reserva not found'], 404);
    }

    DB::table('reservas')->where('id', $reservaId)->delete();

    return response()->json(['message' => 'Reserva deleted successfully'], 200);
}

}
