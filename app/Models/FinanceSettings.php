<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FinanceSettings extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'finance_settings';

    protected $fillable = [
        'starting_cash_balance',
        'annual_budget',
        'funding_goal',
    ];

    protected $casts = [
        'starting_cash_balance' => 'float',
        'annual_budget' => 'float',
        'funding_goal' => 'float',
    ];
}
