<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class BuildingsIntegrations extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'buildings_has_integrations';

    protected $fillable = [
        'buildings_id', 
        'integrations_id',
    ];

}
