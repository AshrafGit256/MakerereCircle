<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Http\Livewire\ClaimForm;
use App\Livewire\Post\Create;
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
    public function boot(): void
    {
        // Livewire::component('claim-form', ClaimForm::class);
        Livewire::component('post.create', Create::class);
    }
}
