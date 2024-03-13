<?php

namespace App\Models\Mistral;

use App\Models\Neumatico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    use HasFactory;

    protected $primaryKey = 'CODIGOOT';
    protected $connection = 'taller';
    protected $table = 'orden_trabajo';

    public function materials()
    {
        return $this->hasMany(Material::class, 'CODIGOOT', 'CODIGOOT');
    }

    public function manoObras()
    {
        return $this->hasMany(ManoObra::class, 'CODIGOOT', 'CODIGOOT');
    }

    public function neumaticos()
    {
        return $this->hasMany(Neumatico::class, 'CODIGOOT', 'CODIGOOT');
    }
}
