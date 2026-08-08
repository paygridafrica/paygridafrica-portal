<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'company_profile';

    protected $fillable = [
        'mission',
        'vision',
        'core_values',           // array of strings
        'company_description',
        'registration_number',
        'registration_date',
        'registered_country',
        'trademark_status',      // e.g. Not Filed, Filed, Registered
        'trademark_number',
        'brand_guidelines_notes',
        'company_status',
'strategic_phase',
'weekly_progress_percent',
'swot_strengths',
'swot_weaknesses',
'swot_opportunities',
'swot_threats',
    ];

    protected $casts = [
        'core_values' => 'array',
        'registration_date' => 'datetime',
    ];
}
