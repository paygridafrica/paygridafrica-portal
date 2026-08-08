<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ResearchNote extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'research_notes';

    protected $fillable = [
        'title',
        'category',   // Market Research, Ticketing Industry, Stadium Technology, Payment Industry, Sports Business, Innovation, Article
        'content',
        'source_url',
    ];
}
