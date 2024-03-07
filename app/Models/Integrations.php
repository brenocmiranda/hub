<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Integrations extends Model
{
    use HasFactory, SoftDeletes;

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
