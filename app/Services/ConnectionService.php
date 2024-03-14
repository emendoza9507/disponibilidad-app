<?php

namespace App\Services;

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

        return $connection;
    }
}
