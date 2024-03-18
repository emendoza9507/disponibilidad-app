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
        return $this->created_at->format('y') . $this->id;
    }

    public function anterior()
    {
        return $this->neumatico_anterior ? $this->created_at->format('y') . $this->neumatico_anterior : null;
    }
}
