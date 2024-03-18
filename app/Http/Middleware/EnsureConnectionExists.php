<?php

namespace App\Http\Middleware;

use App\Helpers\FlashMessage;
use App\Models\Connection;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class EnsureConnectionExists
{

    use FlashMessage;

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Connection::all()->count() == 0) {
            self::warning('No exsiten conecciones');
            return redirect(route('connections.index'));
        }

        return $next($request);
    }
}
