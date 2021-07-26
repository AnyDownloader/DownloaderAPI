<?php

return [
    /*
     | Downloader handlers configuration (on => true/false, s3 bucket in case of caching media contents)
     */
    'handlers' => [
        'instagram' => [
            'on' => env('DOWNLOADER_INSTAGRAM_ON', true),
            'bucket' => env('DOWNLOADER_INSTAGRAM_BUCKET', 'instagram')
        ],
        'tiktok' => [
            'on' => env('DOWNLOADER_TIKTOK_ON', true),
            'bucket' => env('DOWNLOADER_TIKTOK_BUCKET', 'tiktok')
        ],
        'redgifs' => [
            'on' => env('DOWNLOADER_REDGIFS_ON', true),
            'bucket' => env('DOWNLOADER_REDGIFS_BUCKET', 'redgifs')
        ],
        'twitter' => [
            'on' => env('DOWNLOADER_TWITTER_ON', true)
        ],
        'pinterest' => [
            'on' => env('DOWNLOADER_PINTEREST_ON', true)
        ],
        'youtube' => [
            'on' => env('DOWNLOADER_YOUTUBE_ON', true)
        ],
        'reddit' => [
            'on' => env('DOWNLOADER_REDDIT_ON', true)
        ]
    ],

    /*
     |  S3 storage configuration for caching media files that were extracted with downloaders
     */
    's3' => [
        'credentials' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY')
        ],
        'region' => env('AWS_DEFAULT_REGION'),
        'endpoint' => env('AWS_ENDPOINT'),
        'bucket_endpoint' => false,
        'use_path_style_endpoint' => true,
        'version' => 'latest'
    ],

    /*
     |  Twitter app credentials. Required if you want to use Twitter downloader
     |  see https://developer.twitter.com/en/docs/getting-started
     */
    'twitter_credentials' => [
        'key' => env('TWITTER_CONSUMER_KEY', ''),
        'secret' => env('TWITTER_CONSUMER_SECRET', '')
    ],

    'proxy' => []
];