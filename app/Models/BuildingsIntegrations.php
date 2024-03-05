<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingsIntegrations extends Model
{
    use HasFactory;

    protected $table = 'buildings_has_integrations';

    protected $fillable = [
        'building_id',
        'integration_id',
    ];
}
