<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductTask extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'product_tasks';

    protected $fillable = [
        'product_id',   // links this task to one Product
        'title',
        'status',        // To Do, In Progress, Done
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    // This defines the relationship: a task "belongs to" one product.
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
