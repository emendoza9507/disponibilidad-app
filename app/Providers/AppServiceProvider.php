<?php

namespace App\Providers;

use App\Helpers\FlashMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    use FlashMessage;
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
        //
        view()->composer('partials.messages', function ($view) {
            $messages = self::messages();
            return $view->with('messages', $messages);
        });
    }
}
