<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class PipelinesLog extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'pipelines_log';

    protected $fillable = [
        'header',
        'response',
        'pipelines_id',
    ];
}
