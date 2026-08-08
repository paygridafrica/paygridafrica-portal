<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Goal extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'goals';

    protected $fillable = [
        'title',
        'timeframe',   // Weekly, Monthly
        'period_label', // e.g. "Week of Aug 4" or "August 2026"
        'is_complete',
    ];

    protected $casts = [
        'is_complete' => 'boolean',
    ];
}
