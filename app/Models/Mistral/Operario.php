<?php

namespace App\Models\Mistral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operario extends Model
{
    use HasFactory;

    protected $primaryKey = 'CODIGO';
    protected $table = 'OPERARIO';
    protected $connection = 'taller';
}
