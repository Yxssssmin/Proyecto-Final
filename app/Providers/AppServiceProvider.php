<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        VerifyEmail::$toMailCallback = function($notifiable, $verificationUrl) {
            return (new MailMessage)
                ->subject(Lang::get('Verificar correo electrónico'))
                ->greeting(Lang::get("Buenas ") . $notifiable->name)
                ->line(Lang::get('Por favor, haz clic en el botón de abajo para verificar tu dirección de correo electrónico.'))
                ->action(Lang::get('Verificar ahora'), $verificationUrl)
                ->line(Lang::get('Si no creaste una cuenta, no es necesario tomar ninguna otra acción.'))
                ->salutation(new HtmlString(
                    Lang::get("Saludos.").'<br>' .'<strong>'. Lang::get("Nuestro equipo") . '</strong>'
                ));
        };
    }
}
