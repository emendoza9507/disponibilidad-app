<?php

namespace App\Http\Middleware;

use App\Helpers\FlashMessage;
use App\Models\ConnectionRoleUser;
use App\Services\ConnectionService;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermissionOnTaller
{
    use FlashMessage;

    public function __construct(protected ConnectionService $connectionService)
    {}

    public function handle(Request $request, Closure $next, string $role): Response {

        $user = $request->user();

        if($request->query->get('connection_id')) {
            $this->connectionService->setConnection($request->query->get('connection_id'));
        }

        $connection = $this->connectionService->getCurrentConnection();
        $role = Role::findByName($role, 'web');

        if(ConnectionRoleUser::where(['connection_id' => $connection->id, 'rol_id' => $role->id, 'user_id' => $user->id])->count() == 0) {
            self::danger('No tiene los permisos necesarios en este Taller');
            return back();
        }

        return $next($request);
    }

}
