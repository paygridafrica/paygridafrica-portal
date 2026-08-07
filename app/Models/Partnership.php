<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Partnership extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'partnerships';

    protected $fillable = [
        'organization',
        'contact_name',
        'category',
        'stage',       // Prospect, Contacted, Meeting Scheduled, Negotiation, Pilot Discussion, Agreement, Active Partner
        'notes',
    ];
}
