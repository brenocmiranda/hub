<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class ProductsDestinatarios extends Model
{
    use HasFactory, SoftDeletes, Notifiable, HasUuids;

    protected $fillable = [
        'email',
        'products_id', 
    ];
}
