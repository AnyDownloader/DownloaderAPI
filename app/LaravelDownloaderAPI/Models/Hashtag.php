<?php

namespace App\LaravelDownloaderAPI\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Hashtag
 * @package App\Models
 * @mixin Builder
 */
class Hashtag extends Model
{
    /**
     * @var string
     */
    protected $table = 'downloader_author';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hashtag',
        'ext_source',
        'resources_count'
    ];

    /**
     * @return BelongsToMany
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(
            Resource::class,
            'downloader_hashtag_resource',
            'hashtag_id',
            'resource_id'
        );
    }

}
