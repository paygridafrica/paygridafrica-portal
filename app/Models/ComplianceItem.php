<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ComplianceItem extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'compliance_items';

    protected $fillable = [
        'title',
        'is_complete',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'is_complete' => 'boolean',
        'due_date' => 'datetime',
    ];
}
