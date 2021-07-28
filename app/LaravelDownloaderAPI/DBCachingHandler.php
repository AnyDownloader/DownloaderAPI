<?php
namespace App\LaravelDownloaderAPI;

use AnyDownloader\DownloadManager\Exception\NothingToExtractException;
use AnyDownloader\DownloadManager\Exception\NotValidUrlException;
use AnyDownloader\DownloadManager\Handler\CachingHandler;
use AnyDownloader\DownloadManager\Model\FetchedResource;
use AnyDownloader\DownloadManager\Model\URL;
use App\LaravelDownloaderAPI\Jobs\SaveResourceJob;
use App\LaravelDownloaderAPI\Models\Resource;

class DBCachingHandler extends CachingHandler
{
    /**
     * @param URL $url
     * @return FetchedResource
     * @throws NotValidUrlException
     * @throws NothingToExtractException
     */
    public function fetchResource(URL $url): FetchedResource
    {
        if ($resource = Resource::where(['url' => $url->getValue()])->first()) {
            return $resource->getFetchedResource();
        }
        $fetchedResource = parent::fetchResource($url);
        dispatch(new SaveResourceJob($fetchedResource));
        return $fetchedResource;

    }
}