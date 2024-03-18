<?php

namespace App\Models\Mistral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    use HasFactory;

    protected $table = 'parametro';
    protected $connection = 'taller';
}
