<?php

namespace App\Models\Mistral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperMaestro extends Model
{
    use HasFactory;

    protected $primaryKey = 'CODIGOSM';
    protected $connection = 'taller';
    protected $table = 'super_maestro';

    public $marca;


    public function tipo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'TIPO');
    }

    public function maestro()
    {
        return $this->hasMany(Maestro::class, 'CODIGOSM', 'CODIGOSM');
    }
}
