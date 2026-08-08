<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FundingRequest extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'funding_requests';

    protected $fillable = [
        'investor_name',
        'amount_requested',
        'status',   // Draft, Sent, Under Review, Accepted, Declined
        'notes',
        'request_date',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'amount_requested' => 'float',
    ];
}
