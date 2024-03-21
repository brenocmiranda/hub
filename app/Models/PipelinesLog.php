<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PipelinesLog extends Model
{
    use HasFactory;

     protected $table = 'pipelines_log';

    protected $fillable = [
        'header',
        'response',
        'pipeline_id',
    ];
}
