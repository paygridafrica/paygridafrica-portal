<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Competitor extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'competitors';

    protected $fillable = [
        'name',
        'category',        // Ticketing, Stadium Tech, Payments, Sports Business, Other
        'region',
        'website',
        'strengths',
        'weaknesses',
        'notes',
        'threat_level',    // Low, Medium, High
    ];
}
