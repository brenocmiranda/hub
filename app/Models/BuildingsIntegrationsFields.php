<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingsIntegrationsFields extends Model
{
    use HasFactory;

    protected $table = 'buildings_has_integrations_fields';

    protected $fillable = [
        'name',
        'value',
        'buildings_has_integrations_id',
    ];
}
