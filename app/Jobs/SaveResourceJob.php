<?php

namespace App\Jobs;

use AnyDownloader\DownloadManager\Model\FetchedResource;
use App\Models\Author;
use App\Models\Hashtag;
use App\Models\Resource;

/**
 * Class SaveResourceJob
 * @package App\Jobs
 */
class SaveResourceJob extends Job
{
    /**
     * @var FetchedResource
     */
    protected $fetchedResource;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FetchedResource $fetchedResource)
    {
        $this->fetchedResource = $fetchedResource;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fetchedResourceArr = $this->fetchedResource->toArray();
        $resource = new Resource([
            'ext_id' => $fetchedResourceArr['attributes']['id'] ?? null,
            'ext_source' => $fetchedResourceArr['ext_source'],
            'url' => $fetchedResourceArr['source_url'],
            'title' => $fetchedResourceArr['attributes']['title'] ?? null,
            'text' => $fetchedResourceArr['attributes']['text'] ?? null
        ]);
        $resource->setFetchedResource($this->fetchedResource);

        /**
         * Save author
         */
        if ($fetchedResourceArr['attributes']['author']) {
            $author = $fetchedResourceArr['attributes']['author'];
            $authorModel =
                Author::where(['ext_id' => $author['id'], 'ext_source' => $fetchedResourceArr['ext_source']])
                ->orWhere(['nickname' => $author['nickname'], 'ext_source' => $fetchedResourceArr['ext_source']])->first();

            if (!$authorModel instanceof Author) {
                $authorModel = new Author([
                    'ext_id' => $author['id'],
                    'ext_source' => $fetchedResourceArr['ext_source'],
                    'full_name' => $author['full_name'],
                    'nickname' => $author['nickname']
                ]);
                if (is_array($author['avatar']) && isset($author['avatar']['url'])) {
                    $authorModel->avatar_url = $author['avatar']['url'];
                }
                $authorModel->save();

            }
            $resource->author_id = $authorModel->id;
        }

        $resource->save();

        /**
         * Save hashtags
         */
        if (isset($fetchedResourceArr['attributes']['hashtags']) && count($fetchedResourceArr['attributes']['hashtags'])) {
            foreach($fetchedResourceArr['attributes']['hashtags'] as $hashtag) {
                if ($hashtagModel = Hashtag::where(['hashtag' => $hashtag, 'ext_source' => $fetchedResourceArr['ext_source']])->first()) {
                    $hashtagModel->resources_count++;
                } else {
                    $hashtagModel = new Hashtag([
                        'hashtag' => $hashtag,
                        'ext_source' => $fetchedResourceArr['ext_source']
                    ]);
                }
                $hashtagModel->save();
                $resource->hashtags()->attach($hashtagModel);
            }
        }
    }
}
