<?php

namespace App\Models\Mistral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $primaryKey = 'CODIGOM';
    protected $connection = 'taller';
    protected $table = 'material';
}
