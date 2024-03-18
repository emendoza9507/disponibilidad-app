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

        try {
            DB::connection($connection_name)->getPDO();
            DB::connection($connection_name)->getDatabaseName();
        } catch (\Exception $e) {
            // No está conectado
            return null;
        }



        return DB::connection($connection_name);
    }
}
