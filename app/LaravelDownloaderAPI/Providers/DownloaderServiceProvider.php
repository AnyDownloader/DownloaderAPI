<?php

namespace App\LaravelDownloaderAPI\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use AnyDownloader\DownloadManager\Downloader;
use AnyDownloader\DownloadManager\DownloadManager;
use AnyDownloader\DownloadManager\Handler\BaseHandler;
use AnyDownloader\DownloadManager\Handler\Storage\S3Storage;
use AnyDownloader\InstagramDownloader\InstagramHandler;
use AnyDownloader\PinterestDownloader\PinterestHandler;
use AnyDownloader\RedditDownloader\RedditHandler;
use AnyDownloader\RedGifsDownloader\RedGifsHandler;
use AnyDownloader\TikTokDownloader\TikTokHandler;
use AnyDownloader\TwitterDownloader\TwitterHandler;
use AnyDownloader\YouTubeDownloader\YouTubeHandler;
use App\LaravelDownloaderAPI\DBCachingHandler;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Goutte\Client;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpClient\HttpClient;

class DownloaderServiceProvider extends ServiceProvider
{

    public function register()
    {
        $handlers = config('anydownloader.handlers');
        if (!is_array($handlers) || empty($handlers)) {
            return;
        }

        $this->app->singleton(Downloader::class, function() use ($handlers) {
            $client = new Client();
            $httpClient = HttpClient::create();

            if ($handlers['instagram']['on']) {
                $handlers['instagram']['instance'] = new InstagramHandler($client);
            }

            if ($handlers['pinterest']['on']) {
                $handlers['pinterest']['instance'] = new PinterestHandler($client);
            }

            if ($handlers['reddit']['on']) {
                $handlers['reddit']['instance'] = new RedditHandler($httpClient);
            }

            if ($handlers['redgifs']['on']) {
                $handlers['redgifs']['instance'] = new RedGifsHandler($client);
            }

            if ($handlers['tiktok']['on']) {
                $handlers['tiktok']['instance'] = new TikTokHandler($httpClient);
            }

            if ($handlers['twitter']['on']) {
                $handlers['twitter']['instance'] = new TwitterHandler(
                    new TwitterOAuth(
                        config('anydownloader.twitter_credentials.key'),
                        config('anydownloader.twitter_credentials.secret')
                    )
                );
            }

            if ($handlers['youtube']['on']) {
                $handlers['youtube']['instance'] = new YouTubeHandler($httpClient);
            }

            $s3Client = null;
            $downloadManager = new DownloadManager();
            try {
                foreach($handlers as $handler) {
                    if (!isset($handler['instance'])) {
                        continue;
                    }
                    if (!$handler['instance'] instanceof BaseHandler) {
                        continue;
                    }
                    if (isset($handler['bucket']) && $handler['bucket']) {
                        if (is_null($s3Client)) {
                            $s3Client = new S3Client(config('anydownloader.s3'));
                        }
                        if (!$s3Client->doesBucketExist($handler['bucket'])) {
                            $s3Client->createBucket(['Bucket' => $handler['bucket']]);
                        }
                        $handler['instance'] = new DBCachingHandler(
                            clone $handler['instance'],
                            new S3Storage($s3Client, $handler['bucket'], $handler['cdn_alias'] ?? '')
                        );
                    }
                    $downloadManager->addHandler($handler['instance']);
                }
            } catch (S3Exception $e) {
                foreach($handlers as $handler) {
                    $downloadManager->addHandler($handler['instance']);
                }
            }

            return $downloadManager;
        });
    }
}
