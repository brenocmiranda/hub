<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    protected $fillable = [
        'api',
        'name',
        'email',
        'phone',
        'leads_origin_id',
        'building_id',
        'created_at',
    ];

    public function RelationOrigins()
    {
        return $this->belongsTo(LeadsOrigins::class, 'leads_origin_id', 'id');
    }

    public function RelationFields()
    {
        return $this->hasMany(LeadsFields::class, 'leads_id', 'id');
    }

    public function RelationBuildings()
    {
        return $this->belongsTo(Buildings::class, 'building_id', 'id');
    } 

    public function RelationPipelines()
    {
        return $this->hasMany(Pipelines::class, 'lead_id', 'id');
    } 
}
