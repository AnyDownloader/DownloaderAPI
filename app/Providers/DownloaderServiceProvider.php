<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use AnyDownloader\DownloadManager\Downloader;
use AnyDownloader\DownloadManager\DownloadManager;
use AnyDownloader\DownloadManager\Handler\Storage\S3Storage;
use AnyDownloader\InstagramDownloader\InstagramHandler;
use AnyDownloader\PinterestDownloader\PinterestHandler;
use AnyDownloader\RedditDownloader\RedditHandler;
use AnyDownloader\RedGifsDownloader\RedGifsHandler;
use AnyDownloader\TikTokDownloader\TikTokHandler;
use AnyDownloader\TwitterDownloader\TwitterHandler;
use AnyDownloader\YouTubeDownloader\YouTubeHandler;
use App\DownloadManager\MySQLCachingHandler;
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
                if (!$s3Client->doesBucketExist(env('AWS_INSTAGRAM_BUCKET'))) {
                    $s3Client->createBucket(['Bucket' => env('AWS_INSTAGRAM_BUCKET')]);
                }
                if (!$s3Client->doesBucketExist(env('AWS_TIKTOK_BUCKET'))) {
                    $s3Client->createBucket(['Bucket' => env('AWS_TIKTOK_BUCKET')]);
                }
                if (!$s3Client->doesBucketExist(env('AWS_REDGIFS_BUCKET'))) {
                    $s3Client->createBucket(['Bucket' => env('AWS_REDGIFS_BUCKET')]);
                }
                $downloadManager->addHandler(new MySQLCachingHandler($igHandler, new S3Storage($s3Client, 'instagram')));
                $downloadManager->addHandler(new MySQLCachingHandler($redHandler, new S3Storage($s3Client, 'redgifs')));
                $downloadManager->addHandler(new MySQLCachingHandler($tiktokHandler, new S3Storage($s3Client, 'tiktok')));
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
