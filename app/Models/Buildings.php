<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        return $this->belongsToMany(Integrations::class, 'buildings_has_integrations', 'building_id', 'integration_id');
    }

    public function RelationIntegrationsFields()
    {
        return $this->hasManyThrough('buildings_has_integrations_fields', 'buildings_has_integrations');
    }
}
