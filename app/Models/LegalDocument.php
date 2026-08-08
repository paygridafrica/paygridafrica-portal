<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class LegalDocument extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'legal_documents';

    protected $fillable = [
        'title',
        'category',      // Contract, NDA, Partnership Agreement, License, Other
        'party_name',     // who it's with, if applicable
        'status',         // Draft, Under Review, Signed, Expired
        'signed_date',
        'expiry_date',
        'notes',
    ];

    protected $casts = [
        'signed_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];
}
