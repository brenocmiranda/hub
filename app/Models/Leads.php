<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class Leads extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'api',
        'name',
        'email',
        'phone',
        'batches_id',
        'products_id',
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

    public function RelationProducts()
    {
        return $this->belongsTo(Products::class, 'products_id', 'id')->withTrashed();
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
