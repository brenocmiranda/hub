<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class ProductsIntegrations extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'products_has_integrations';

    protected $fillable = [
        'products_id', 
        'integrations_id',
    ];

}
