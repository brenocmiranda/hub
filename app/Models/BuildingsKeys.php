<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class BuildingsKeys extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'active',
        'value',
        'buildings_id',
    ];

    public function RelationBuildings()
    {
        return $this->belongsTo(Buildings::class, 'buildings_id', 'id');
    }
}
