<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string|null $pageUrl
 * @property int $website_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $html
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage whereHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage wherePageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebPage whereWebsiteId($value)
 * @mixin \Eloquent
 */
class WebPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'pageUrl',
        'website_id',
        'title',
        'description',
        'html',
    ];
}
