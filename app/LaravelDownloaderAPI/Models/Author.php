<?php

namespace App\LaravelDownloaderAPI\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Author
 * @package App\Models
 * @mixin Builder
 */
class Author extends Model
{
    /**
     * @var string
     */
    protected $table = 'downloader_authors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ext_id',
        'ext_source',
        'avatar_url',
        'full_name',
        'nickname'
    ];

    /**
     * @return HasMany
     */
    public function fetchedResources(): HasMany
    {
        return $this->hasMany(Resource::class, 'author_id', 'id');
    }
}
