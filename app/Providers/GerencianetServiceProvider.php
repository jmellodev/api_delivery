<?php

namespace App\Providers;

use Gerencianet\Gerencianet;
use Illuminate\Support\ServiceProvider;

class GerencianetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Gerencianet::class, function () {
            $mode = config('services.gerencianet.mode');
            $options = [
                'client_id' => config("services.gerencianet.{$mode}.client_id"),
                'client_secret' => config("services.gerencianet.{$mode}.client_secret"),
                'certificate' => config("services.gerencianet.{$mode}.certificate"),
                'sandbox' => $mode === 'sandbox',
                'debug' => config("services.gerencianet.debug"),
                'timeout' => 30,
            ];
            // dd($mode);
            return new Gerencianet($options);
        });
    }
}
