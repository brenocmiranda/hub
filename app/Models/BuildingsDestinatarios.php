<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuildingsDestinatarios extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'email',
        'buildings_id', 
    ];
}
