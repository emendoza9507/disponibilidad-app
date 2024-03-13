<?php

namespace App\Models\Mistral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maestro extends Model
{
    use HasFactory;

    protected $primaryKey = 'CODIGOM';
    protected $connection = 'taller';
    protected $table = 'maestro';


    public function supermaestro() {
        return $this->belongsTo(SuperMaestro::class, 'CODIGOSM', 'CODIGOSM');
    }

}
