<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class DecisionLogEntry extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'decision_log';

    protected $fillable = [
        'decision',
        'reasoning',
        'decision_date',
    ];

    protected $casts = [
        'decision_date' => 'datetime',
    ];
}
