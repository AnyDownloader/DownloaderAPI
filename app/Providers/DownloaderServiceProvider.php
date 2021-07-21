<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use AnyDownloader\DownloadManager\Downloader;
use AnyDownloader\DownloadManager\DownloadManager;
use AnyDownloader\DownloadManager\Handler\CachingHandler;
use AnyDownloader\DownloadManager\Handler\Storage\S3Storage;
use AnyDownloader\InstagramDownloader\InstagramHandler;
use AnyDownloader\PinterestDownloader\PinterestHandler;
use AnyDownloader\RedditDownloader\RedditHandler;
use AnyDownloader\RedGifsDownloader\RedGifsHandler;
use AnyDownloader\TikTokDownloader\TikTokHandler;
use AnyDownloader\TwitterDownloader\TwitterHandler;
use AnyDownloader\YouTubeDownloader\YouTubeHandler;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Goutte\Client;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpClient\HttpClient;

class DownloaderServiceProvider extends ServiceProvider
{
    /**
     * Register DownloadManager with needed handlers
     *
     * @return void
     */
    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton(Downloader::class, function() {
            $client = new Client();
            $s3Client = new S3Client([
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY')
                ],
                'region' => env('AWS_DEFAULT_REGION'),
                'bucket_endpoint' => false,
                'endpoint' => env('AWS_ENDPOINT'),
                'use_path_style_endpoint' => true,
                'version' => 'latest'
            ]);

            $downloadManager = new DownloadManager();
            $igHandler = new InstagramHandler($client);
            $pinHandler = new PinterestHandler($client);
            $redHandler = new RedGifsHandler($client);
            $httpClient = HttpClient::create();
            $ytHandler = new YouTubeHandler($httpClient);
            $redditHandler = new RedditHandler($httpClient);
            $tiktokHandler = new TikTokHandler($httpClient);
            $tw = new TwitterHandler(new TwitterOAuth(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET')));
            try {
                if (!$s3Client->doesBucketExist('instagram')) {
                    $s3Client->createBucket(['Bucket' => 'instagram']);
                }
                if (!$s3Client->doesBucketExist('tiktok')) {
                    $s3Client->createBucket(['Bucket' => 'tiktok']);
                }
                if (!$s3Client->doesBucketExist('redgifs')) {
                    $s3Client->createBucket(['Bucket' => 'redgifs']);
                }
                $downloadManager->addHandler(new CachingHandler($igHandler, new S3Storage($s3Client, 'instagram')));
                $downloadManager->addHandler(new CachingHandler($redHandler, new S3Storage($s3Client, 'redgifs')));
                $downloadManager->addHandler(new CachingHandler($tiktokHandler, new S3Storage($s3Client, 'tiktok')));
            } catch (S3Exception $e) {
                $downloadManager->addHandler($igHandler);
                $downloadManager->addHandler($redHandler);
                $downloadManager->addHandler($tiktokHandler);
            }
            $downloadManager->addHandler($ytHandler);
            $downloadManager->addHandler($pinHandler);
            $downloadManager->addHandler($tw);
            $downloadManager->addHandler($redditHandler);

            return $downloadManager;
        });
    }
}
