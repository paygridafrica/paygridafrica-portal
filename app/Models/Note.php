<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Note extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'notes';

    protected $fillable = [
        'type',     // Personal Note, Daily Planner, Vision Board, Journal, Motivation
        'content',
        'entry_date',
    ];

    protected $casts = [
        'entry_date' => 'datetime',
    ];
}
