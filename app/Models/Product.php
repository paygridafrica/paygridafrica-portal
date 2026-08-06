<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'products';

    protected $fillable = [
        'name',
        'description',
        'development_stage',   // Concept, Design, Development, Testing, Launched
        'priority',             // Low, Medium, High, Critical
        'version',
        'screens_completed',
        'screens_total',
    ];
}
