<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neumatico extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function connection()
    {
        return $this->hasOne(Connection::class,'codigo_taller', 'TALLER');
    }

    public function consecutivo() {
        if($this->id > 9999) {
            return substr((string) $this->id, strlen((string) $this->id) - 4);
        }

        return $this->id;
    }

    public function anterior()
    {
        return $this->neumatico_anterior ?  $this->neumatico_anterior : null;
    }
}
