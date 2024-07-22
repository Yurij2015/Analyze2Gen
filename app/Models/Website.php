<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'baseDomain',
        'title',
        'description',
        'keywords',
        'robots',
        'canonical',
        'general',
        'googleTag',
        'facebookPixel',
        'siteLinks',
        'siteMap',
    ];

    protected $casts = [
        'siteLinks' => 'array',
        'siteMap' => 'array',
    ];
}
