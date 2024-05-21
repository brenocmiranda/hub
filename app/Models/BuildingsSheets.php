<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuildingsSheets extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'spreadsheetID',
        'sheet',
        'file',
        'buildings_id', 
    ];
}
