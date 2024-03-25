<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuildingsKeys extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'active',
        'value',
        'building_id',
    ];

    public function RelationBuildings()
    {
        return $this->belongsTo(Buildings::class, 'building_id', 'id');
    }
}
