<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integrations extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'name',
        'slug',
        'url',
        'user',
        'password',
        'token',
        'header',
    ];
}
