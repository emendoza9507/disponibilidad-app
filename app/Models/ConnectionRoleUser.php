<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role;

class ConnectionRoleUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = null;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'rol_id', 'id');
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(Connection::class);
    }
}
