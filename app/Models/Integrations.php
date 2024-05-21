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
        'type',
        'name',
        'slug',
        'url',
        'encoded',
        'user',
        'password',
        'token',
        'header',
        'companies_id',
    ];

    public function RelationCompanies()
    {
        return $this->belongsTo(Companies::class, 'companies_id', 'id');
    }
}
