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
            'name' => 'CIENAGA',
            'database' => 'Taller6h09',
            'hostname' => '10.105.231.166',
            'username' => 'sa',
            'password' => 'lulu2000',
            'description' => 'Taller Josefina y Revolucion',
            'codigo_taller' => '6H09'
        ]);

        Connection::create([
            'name' => 'PRIMELLES',
            'database' => 'Taller6h58',
            'hostname' => '10.105.198.34',
            'username' => 'sa',
            'password' => 'lulu2000',
            'description' => 'Taller Primelles',
            'codigo_taller' => '6H58'
        ]);

        Connection::create([
            'name' => 'JOSEFINA',
            'database' => 'Taller6h56',
            'hostname' => '10.105.219.254',
            'username' => 'sa',
            'password' => 'lulu2000',
            'description' => 'Taller Josefina y Revolucion',
            'codigo_taller' => '6H56'
        ]);

        Connection::create([
            'name' => 'PASEO',
            'database' => 'Taller6h05',
            'hostname' => '10.105.206.163',
            'username' => 'sa',
            'password' => 'Abc123456',
            'description' => 'Taller Josefina y Revolucion',
            'codigo_taller' => '6H05'
        ]);
    }
}
