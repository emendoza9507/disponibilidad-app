<?php

namespace App\Providers;

use App\Helpers\FlashMessage;
use App\Models\Connection;
use App\Models\ConnectionRoleUser;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{

    protected $connections = [];

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

        $this->connections = Connection::paginate(30);

        Blade::if('role', function (string $connection = null, string $role = null) {
            if($connection != null && $role != null) {
                $user = auth()->user();
                $role = Role::findByName($role, 'web');


                return ConnectionRoleUser::where(['connection_id' => $connection, 'rol_id' => $role->id, 'user_id' => $user->id])->first() != null;
            } else {
                return true;
            }
        });

        //
        view()->composer('partials.messages', function ($view) {
            $messages = self::messages();
            return $view->with('messages', $messages);
        });

        view()->composer('*', function ($view) {
            return $view->with('connections', $this->connections);
        });
    }
}
