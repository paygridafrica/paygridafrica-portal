<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Bug extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'bugs';

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'severity',  // Low, Medium, High, Critical
        'status',    // Open, In Progress, Fixed
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
