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
}
