<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'action',
        'user_id',
    ];

    public function RelationUsers()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
