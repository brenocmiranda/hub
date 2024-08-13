<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class BuildingsSheets extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'spreadsheetID',
        'sheet',
        'file',
        'buildings_id', 
    ];
}
