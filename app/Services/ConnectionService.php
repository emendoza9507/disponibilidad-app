<?php

namespace App\Services;

use App\Helpers\ResetDB;
use App\Models\Connection;
use App\Models\Mistral\Parametro;

class ConnectionService
{
    public function setConnection($connection_id)
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

        ResetDB::setDBConfig('taller', (array) session('connection'));

        return $connection;
    }

    public function getCurrentConnection()
    {
        $session_connection_id = session('connection') ? session('connection')['connection_id'] : 1;

        return $session_connection_id ? Connection::find($session_connection_id) : null;
    }
}
