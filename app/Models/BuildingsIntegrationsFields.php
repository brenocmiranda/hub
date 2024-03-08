<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuildingsIntegrationsFields extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'buildings_has_integrations_fields';

    protected $fillable = [
        'name',
        'value', 
        'buildings_has_integrations_building_id',
        'buildings_has_integrations_integration_id',
    ];

    public function RelationBuildings()
    {
        return $this->belongsTo(Buildings::class, 'buildings_has_integrations_building_id', 'id');
    }

    public function RelationIntegrations()
    {
        return $this->belongsTo(Integrations::class, 'buildings_has_integrations_integration_id', 'id');
    }
}
