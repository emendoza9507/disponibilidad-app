<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoOT extends Model
{
    public const IDS_ESTADOS_ANULADOS = [9, 2];

    use HasFactory;

    protected $primaryKey = 'estado_id';
    protected $connection = 'mysql';
    public $timestamps = false;

    protected $fillable = [
        'estado_id',
        'estado_nombre'
    ];
}
