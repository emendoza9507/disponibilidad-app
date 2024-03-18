<?php

namespace App\Models\Mistral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $primaryKey = 'CODIGOOT';
    protected $connection = 'taller';
    protected $table = 'material';

    public function orden()
    {
        return $this->belongsTo(OrdenTrabajo::class, 'CODIGOOT', 'CODIGOOT');
    }
}
