<?php

namespace App\LaravelDownloaderAPI\Models;

use AnyDownloader\DownloadManager\Model\FetchedResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Resource
 * @package App\Models
 * @mixin Builder
 */
class Resource extends Model
{
    /**
     * @var string
     */
    protected $table = 'downloader_resources';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ext_id',
        'ext_source',
        'url',
        'title',
        'text',
        'author_id'
    ];

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * @return BelongsToMany
     */
    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(
            Hashtag::class,
            'downloader_hashtag_resource',
            'resource_id',
            'hashtag_id'
        );
    }

    /**
     * @return FetchedResource
     */
    public function getFetchedResource(): FetchedResource
    {
        return unserialize($this->attributes['serialized_fetched_resource']);
    }

    /**
     * @param FetchedResource $fetchedResource
     */
    public function setFetchedResource(FetchedResource $fetchedResource)
    {
        $this->attributes['serialized_fetched_resource'] = serialize($fetchedResource);
    }

}
