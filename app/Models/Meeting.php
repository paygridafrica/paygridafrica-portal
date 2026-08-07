<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Meeting extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'meetings';

    protected $fillable = [
        'contact_name',
        'organization',
        'position',
        'email',
        'phone',
        'category',        // Football, Music, Government, Telecom, Banks, Investors, Event Organizers
        'meeting_date',
        'notes',
        'follow_up_date',
        'status',           // e.g. Scheduled, Completed, Cancelled
        'probability',      // e.g. Low, Medium, High
        'relationship_strength', // e.g. New, Warm, Strong
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'follow_up_date' => 'datetime',
    ];
}
