<?php

namespace App\Services;

use App\Models\Connection;

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
                'password' => $connection->password
            ]]);
        }

        return $connection;
    }
}
