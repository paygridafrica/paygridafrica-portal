<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Milestone extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'milestones';

    protected $fillable = [
        'title',
        'description',
        'milestone_date',
        'status', // Planned, In Progress, Achieved
    ];

    protected $casts = [
        'milestone_date' => 'datetime',
    ];
}
