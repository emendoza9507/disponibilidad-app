<?php

namespace Database\Seeders;

use App\Models\Connection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Connection::create([
            'name' => 'PRIMELLES',
            'database' => 'Taller6h58',
            'hostname' => '127.0.0.1',
            'username' => 'sa',
            'password' => 'lulu2000*',
            'description' => 'Taller Primelles',
            'codigo_taller' => '6H58'
        ]);

        Connection::create([
            'name' => 'JOSEFINA',
            'database' => 'Taller6h56',
            'hostname' => '127.0.0.1',
            'username' => 'sa',
            'password' => 'lulu2000*',
            'description' => 'Taller Josefina y Revolucion',
            'codigo_taller' => '6H56'
        ]);
    }
}
