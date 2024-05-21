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
    ];

    public function RelationIntegrations()
    {
        return $this->belongsToMany(Integrations::class, 'buildings_has_integrations', 'buildings_id', 'integrations_id')->whereNull('buildings_has_integrations.deleted_at');
    }

    public function RelationIntegrationsFields()
    {
        return $this->hasMany(BuildingsIntegrationsFields::class, 'buildings_has_integrations_buildings_id', 'id');
    }

    public function RelationPartners() 
    {
        return $this->hasMany(BuildingsPartners::class, 'buildings_id', 'id');
    }

    public function RelationDestinatarios() 
    {
        return $this->hasMany(BuildingsDestinatarios::class, 'buildings_id', 'id');
    }

    public function RelationSheets() 
    {
        return $this->hasMany(BuildingsSheets::class, 'buildings_id', 'id');
    }

    public function RelationKeys() 
    {
        return $this->hasMany(BuildingsKeys::class, 'buildings_id', 'id');
    }
}
