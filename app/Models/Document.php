<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Document extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'documents';

    protected $fillable = [
        'title',
        'category',      // Proposal, Business Plan, Pitch Deck, Prototype Book, Presentations, Company Policies, Letters, Meeting Minutes
        'file_path',       // where the actual file lives on disk
        'original_filename',
        'file_size',
        'uploaded_by',
    ];
}
