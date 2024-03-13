<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ResetDB
{
    public static function setDBConfig($connection_name, $config)
    {
        $config_connection = [
            'driver' => 'sqlsrv',
            'host' =>   $config['host'],
            'port' => $config['port'],
            'database' => $config['database'],
            'username' => $config['username'],
            'password' => $config['password']
        ];

        $CONFIG = 'database.connections.'.$connection_name;

        Config::set($CONFIG, $config_connection);

        DB::purge($connection_name);

        return DB::connection($connection_name);
    }
}
