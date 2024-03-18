<?php

namespace App\Services;

use App\Helpers\ResetDB;
use App\Models\Connection;
use App\Models\Mistral\Parametro;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class ConnectionService
{
    public function setConnection($connection_id, \Closure $callback = null)
    {
        $connection = Connection::find($connection_id);

        if($connection) {
            session(['connection' => [
                'host' => $connection->hostname,
                'port' => '1433',
                'database' => $connection->database,
                'username' => $connection->username,
                'password' => $connection->password,
                'connection_id' => $connection_id
            ]]);
        }

        $connect = ResetDB::setDBConfig('taller', (array) session('connection'));

        if(!$connect) {
            if($callback) {
                return $callback();
            } else {
                return null;
            }
        }

        return $connection;
    }

    public function getCurrentConnection()
    {
        $session_connection_id = session('connection') ? session('connection')['connection_id'] : 1;

        return $session_connection_id ? Connection::find($session_connection_id) : null;
    }
}
