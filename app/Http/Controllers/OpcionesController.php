<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class OpcionesController extends Controller
{
    public function opciones() {
        return Inertia::render('Opciones', [
            'title' => 'Opciones',
            'links' => [
                [
                    'title' => 'Mis Reservas',
                    'description' => 'Aquí puedes ver tus reservas.',
                    'href' => '/mis-reservas'
                ],
                [
                    'title' => 'Reservar',
                    'description' => 'Haz una nueva reserva.',
                    'href' => '/reservar'
                ],
                [
                    'title' => 'Mi Perfil',
                    'description' => 'Ve o edita tu perfil.',
                    'href' => '/perfil'
                ]
            ]
        ]);
    }
    
}
