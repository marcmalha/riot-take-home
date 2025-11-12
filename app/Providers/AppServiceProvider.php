<?php

namespace App\Providers;

use App\Services\Encryptor\Contracts\Encryptor as EncryptorContract;
use App\Services\Encryptor\Encryptor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Below is the framework mechanism for dependency injection ; we're ensuring dependency inversion
        // between the EncryptionController and the Encryptor service
        $this->app->bind(EncryptorContract::class, Encryptor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
