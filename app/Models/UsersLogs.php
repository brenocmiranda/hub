<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class UsersLogs extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'title',
        'description',
        'action',
        'users_id',
    ];

    public function RelationUsers()
    {
        return $this->belongsTo(Users::class, 'users_id', 'id');
    }
}
