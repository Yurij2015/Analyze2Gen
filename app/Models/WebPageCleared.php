<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPageCleared extends Model
{
    use HasFactory;

    protected $fillable = [
        'web_page_id',
        'page_title',
        'level',
        'node_name',
        'content_length',
        'base_url',
        'child_nodes',
        'parent_node',
        'content_title',
        'content',
        'node_count',
    ];

    protected $casts = [
        'child_nodes' => 'array',
    ];
}
