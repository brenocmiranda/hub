<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class ProductsKeys extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'active',
        'value',
        'products_id',
    ];

    public function RelationProducts()
    {
        return $this->belongsTo(Products::class, 'products_id', 'id');
    }
}
