<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $project_id
 * @property string|null $baseDomain
 * @property string|null $title
 * @property string|null $description
 * @property string|null $keywords
 * @property string|null $robots
 * @property string|null $canonical
 * @property string|null $general
 * @property int $googleTag
 * @property int $facebookPixel
 * @property array|null $siteLinks
 * @property array|null $siteMap
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Website newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Website newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Website query()
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereBaseDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereCanonical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereFacebookPixel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereGeneral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereGoogleTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereRobots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereSiteLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereSiteMap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
