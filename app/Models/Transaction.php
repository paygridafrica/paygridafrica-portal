<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Transaction extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'transactions';

    protected $fillable = [
        'type',         // Income, Expense
        'category',     // e.g. Salaries, Software, Legal, Marketing, Investment, Pilot Revenue
        'amount',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'float',
    ];
}
