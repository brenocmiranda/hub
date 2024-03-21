<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buildings extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'active',
        'name',
        'companie_id',
    ];

    public function RelationCompanies() 
    {
        return $this->belongsTo(Companies::class, 'companie_id', 'id');
    }

    public function RelationIntegrations()
    {
        return $this->belongsToMany(Integrations::class, 'buildings_has_integrations', 'building_id', 'integration_id')->whereNull('buildings_has_integrations.deleted_at');
    }

    public function RelationIntegrationsFields()
    {
        return $this->hasMany(BuildingsIntegrationsFields::class, 'buildings_has_integrations_building_id', 'id');
    }
}
