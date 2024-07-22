<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $project_id
 * @property array $websites
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteList query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteList whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteList whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteList whereWebsites($value)
 * @mixin \Eloquent
 */
class WebsiteList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'websites',
    ];

    protected $casts = [
        'websites' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
