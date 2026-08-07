<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Investor extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'investors';

    protected $fillable = [
        'name',
        'firm',
        'funding_stage',        // e.g. Pre-Seed, Seed, Series A
        'email',
        'phone',
        'pitch_deck_sent',      // true/false
        'notes',
        'follow_up_date',
        'investment_probability', // Low, Medium, High
        'required_documents',   // array of strings, e.g. ["Pitch Deck", "Financial Model", "Cap Table"]
    ];

    protected $casts = [
        'follow_up_date' => 'datetime',
        'pitch_deck_sent' => 'boolean',
        'required_documents' => 'array',
    ];
}
