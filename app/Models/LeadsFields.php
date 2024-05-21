<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadsFields extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'value',
        'leads_id',
    ];
}
