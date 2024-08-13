<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class BuildingsIntegrationsFields extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'buildings_has_integrations_fields';

    protected $fillable = [
        'name',
        'value', 
        'buildings_id',
        'integrations_id',
    ];

    public function RelationBuildings()
    {
        return $this->belongsTo(Buildings::class, 'buildings_id', 'id');
    }

    public function RelationIntegrations()
    {
        return $this->belongsTo(Integrations::class, 'integrations_id', 'id');
    }
}
