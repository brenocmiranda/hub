<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class ProductsPartners extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'main',
        'leads',
        'companies_id',
        'products_id',
    ];

    public function RelationCompanies()
    {
        return $this->belongsTo(Companies::class, 'companies_id', 'id');
    }

    public function RelationProducts()
    {
        return $this->belongsTo(Products::class, 'products_id', 'id');
    }
}
