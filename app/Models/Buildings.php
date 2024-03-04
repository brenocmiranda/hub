<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buildings extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'name',
        'companie_id',
    ];

    public function RelationCompanies()
    {
        return $this->belongsTo(Companies::class, 'companie_id', 'id');
    }
}
