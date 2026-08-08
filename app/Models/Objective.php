<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Objective extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'objectives';

    protected $fillable = [
        'title',
        'quarter',    // e.g. "Q3 2026"
        'status',     // Planned, In Progress, Achieved
        'description',
    ];
}
