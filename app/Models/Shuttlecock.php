<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shuttlecock extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'stock',
        'price',
        'is_available',
    ];

    
}
