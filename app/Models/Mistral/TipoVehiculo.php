<?php

namespace App\Models\Mistral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    use HasFactory;

    protected $primaryKey = 'CODIGO';
    protected $connection = 'taller';
    protected $table = 'TBL_TIPOVEHICULO';
    protected $keyType = 'string';
}
