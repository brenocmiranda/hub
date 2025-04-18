<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class BuildingsPartners extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'main',
        'leads',
        'companies_id',
        'buildings_id',
    ];

    public function RelationCompanies()
    {
        return $this->belongsTo(Companies::class, 'companies_id', 'id');
    }

    public function RelationBuildings()
    {
        return $this->belongsTo(Buildings::class, 'buildings_id', 'id');
    }
}
