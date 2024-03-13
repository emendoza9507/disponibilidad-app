<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Eduardo Mendoza Campos',
            'email' => 'emendoza9507@gmail.com',
            'password' => bcrypt('matahambre')
        ])->assignRole('admin');
    }
}
