<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class Products extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'active',
        'name',
        'products_id'
    ];

    public function RelationIntegrations()
    {
        return $this->belongsToMany(Integrations::class, 'products_has_integrations', 'products_id', 'integrations_id')->whereNull('products_has_integrations.deleted_at');
    }

    public function RelationIntegrationsFields()
    {
        return $this->hasMany(ProductsIntegrationsFields::class, 'products_id', 'id');
    }

    public function RelationPartners() 
    {
        return $this->hasMany(ProductsPartners::class, 'products_id', 'id');
    }

    public function RelationDestinatarios() 
    {
        return $this->hasMany(ProductsDestinatarios::class, 'products_id', 'id');
    }

    public function RelationSheets() 
    {
        return $this->hasMany(ProductsSheets::class, 'products_id', 'id');
    }

    public function RelationKeys() 
    {
        return $this->hasMany(ProductsKeys::class, 'products_id', 'id');
    }

    public function RelationProducts() 
    {
        return $this->belongsTo(Products::class, 'products.products_id', 'id');
    }
}
