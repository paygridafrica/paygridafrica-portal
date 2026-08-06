<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FeatureRequest extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'feature_requests';

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'status', // Requested, Planned, In Progress, Shipped, Declined
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
