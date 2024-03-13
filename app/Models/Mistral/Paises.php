<?php

namespace App\Models\Mistral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paises extends Model
{
    use HasFactory;

    protected $primaryKey = 'CODIGO';
    protected $table = 'TBL_PAISES';
    protected $keyType = 'string';
}
