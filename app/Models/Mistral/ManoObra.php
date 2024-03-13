<?php

namespace App\Models\Mistral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManoObra extends Model
{
    use HasFactory;

    protected $primaryKey = 'CODIGOOT';
    protected $table = 'mano_obra';

}
