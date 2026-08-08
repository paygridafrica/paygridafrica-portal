<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Risk extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'risks';

    protected $fillable = [
        'title',
        'category',      // Financial, Legal, Product, Market, Team, Other
        'likelihood',     // Low, Medium, High
        'impact',         // Low, Medium, High
        'mitigation_plan',
        'status',         // Open, Monitoring, Resolved
    ];
}
