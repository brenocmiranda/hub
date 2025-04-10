<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class ProductsIntegrationsFields extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'products_has_integrations_fields';

    protected $fillable = [
        'name',
        'value', 
        'products_id',
        'integrations_id',
    ];

    public function RelationProducts()
    {
        return $this->belongsTo(Products::class, 'products_id', 'id');
    }

    public function RelationIntegrations()
    {
        return $this->belongsTo(Integrations::class, 'integrations_id', 'id');
    }
}
