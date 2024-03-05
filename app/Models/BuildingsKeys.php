<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingsKeys extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'name',
        'value',
        'building_id',
    ];

    public function RelationBuildings()
    {
        return $this->belongsTo(Buildings::class, 'building_id', 'id');
    }
}
