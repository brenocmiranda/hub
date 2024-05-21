<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuildingsIntegrations extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'buildings_has_integrations';

    protected $fillable = [
        'buildings_id', 
        'integrations_id',
    ];

}
