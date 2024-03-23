<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pipelines extends Model
{
    use HasFactory;

    protected $fillable = [
        'statusCode',
        'lead_id',
        'buildings_has_integrations_building_id',
        'buildings_has_integrations_integration_id',
    ];

    public function RelationLeads()
    {
        return $this->belongsTo(Leads::class, 'lead_id', 'id');
    }

    public function RelationPipelinesLog()
    {
        return $this->hasOne(PipelinesLog::class, 'pipeline_id', 'id');
    } 

    public function RelationIntegrations()
    {
        return $this->belongsTo(Integrations::class, 'buildings_has_integrations_integration_id', 'id');
    } 
}
