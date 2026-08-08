<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class MediaAsset extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'media_assets';

    protected $fillable = [
        'title',
        'category',    // Logos, Images, Videos, Screenshots, Marketing Materials, Brand Assets
        'file_path',
        'original_filename',
        'file_size',
        'mime_type',
        'uploaded_by',
    ];
}
