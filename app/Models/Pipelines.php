<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pipelines extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'statusCode',
        'attempts',
        'leads_id',
        'buildings_id',
        'integrations_id',
    ];

    public function RelationLeads()
    {
        return $this->belongsTo(Leads::class, 'leads_id', 'id');
    }

    public function RelationPipelinesLog()
    {
        return $this->hasOne(PipelinesLog::class, 'pipelines_id', 'id');
    } 

    public function RelationIntegrations()
    {
        return $this->belongsTo(Integrations::class, 'integrations_id', 'id');
    } 
}
