<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PipelinesLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pipelines_log';

    protected $fillable = [
        'header',
        'response',
        'pipelines_id',
    ];
}
