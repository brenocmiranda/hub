<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leads extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'api',
        'name',
        'email',
        'phone',
        'batches_id',
        'buildings_id',
        'leads_origins_id',
        'companies_id',
        'created_at',
    ];

    public function RelationOrigins()
    {
        return $this->belongsTo(LeadsOrigins::class, 'leads_origins_id', 'id');
    }

    public function RelationFields()
    {
        return $this->hasMany(LeadsFields::class, 'leads_id', 'id');
    }

    public function RelationBuildings()
    {
        return $this->belongsTo(Buildings::class, 'buildings_id', 'id')->withTrashed();
    } 

    public function RelationPipelines()
    {
        return $this->hasMany(Pipelines::class, 'leads_id', 'id');
    } 

    public function RelationCompanies()
    {
        return $this->belongsTo(Companies::class, 'companies_id', 'id');
    } 

    public function RelationBatches()
    {
        return $this->belongsTo(JobBatches::class, 'bactches_id', 'id');
    } 
}
