# Lumen + Laravel-DownloaderAPI

This is ready-to-use Lumen Application with installed [Laravel-DownloaderAPI](https://github.com/AnyDownloader/Laravel-DownloaderAPI) package

```
cp .env.example .env
```

Set correct env variables inside of `.env` file. Read more in official [Laravel docs](https://laravel.com/docs/8.x/configuration)

```
composer update

php artisan migrate
```

## API endpoint(s)

| Method | URI | Params | Response |
| ------ | --- | ------ | -------- | 
| GET | /api/resource | url | see example at the end|

## List of supported sources

-   [RedGifs Video Downloader](https://github.com/AnyDownloader/RedGifsDownloader)
-   [Reddit Video Downloader](https://github.com/AnyDownloader/RedditDownloader)
-   [Twitter Video Downloader](https://github.com/AnyDownloader/TwitterDownloader)
-   [Instagram Image/Video Post Downloader](https://github.com/AnyDownloader/InstagramDownloader)
-   [Pinterest Image/Video Downloader](https://github.com/AnyDownloader/PinterestDownloader)
-   [YouTube Audio/Video/Thumbnail/Subtitles Downloader](https://github.com/AnyDownloader/YouTubeDownloader)
-   [TikTok Audio/Video Downloader](https://github.com/AnyDownloader/TikTokDownloader)

## Configuration

This package supports caching of media contents on S3 with persisting data in MySQL

By default, only Instagram, TikTok, and RedGifs files are caching.
In [config](https://github.com/AnyDownloader/Laravel-DownloaderAPI/blob/master/config/anydownloader.php) you can specify which handlers to use and contents from which sources to cache. Also you can provide S3 buckets for each channel


#### GET /api/resource?url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DzrMW2Gs6Egg
```yaml
{
  data: {
    ext_source: "youtube",
    source_url: "https://www.youtube.com/watch?v=zrMW2Gs6Egg",
    preview_image: {
      type: "image",
      format: "jpg",
      url: "https://i.ytimg.com/vi/zrMW2Gs6Egg/maxresdefault.jpg",
      mime_type: "image/jpg",
      title: "1920x1080"
    },
    preview_video: {
      type: "video",
      format: "mp4",
      url: "https://r4---sn-w5nuxa-c33ls.googlevideo.com/videoplayback?.........",
      mime_type: "video/mp4",
      title: "720p"
    },
    attributes: {
      title: "Ari Shaffir on Tom Segura's Dunk Injury",
      hashtags: [
        "Joe Rogan Experience",
        "JRE",
        "Joe",
        "Rogan",
        "podcast",
        "MMA",
        "comedy",
        "stand",
        "up",
        "funny",
        "Freak",
        "Party"
      ],
      views_count: "415191",
      author: {
        id: "UCzQUP1qoWDoEbmsQxvdjxgQ",
        avatar_url: null,
        full_name: "PowerfulJRE",
        nickname: "PowerfulJRE",
        avatar: null
      }
    },
    items: {
      image: [
        {
          type: "image",
          format: "jpg",
          url: "https://i.ytimg.com/vi/zrMW2Gs6Egg/maxresdefault.jpg",
          mime_type: "image/jpg",
          title: "1920x1080"
        }
        ],
      text: [
        {
          type: "text",
          format: "xml",
          url: "https://www.youtube.com/api/timedtext?....",
          mime_type: "text/xml",
          title: "en"
        }
      ],
      video: [
        {
          type: "video",
          format: "mp4",
          url: "https://r4---sn-w5nuxa-c33ls.googlevideo.com/videoplayback?...",
          mime_type: "video/mp4",
          title: "360p"
        },
        {
          type: "video",
          format: "mp4",
          url: "https://r4---sn-w5nuxa-c33ls.googlevideo.com/videoplayback?...",
          mime_type: "video/mp4",
          title: "720p"
        }
      ],
      audio: [
        {
          type: "audio",
          format: "mp4",
          url: "https://r4---sn-w5nuxa-c33ls.googlevideo.com/videoplayback?...",
          mime_type: "audio/mp4",
          title: "130327"
        }
      ]
    }
  },
  message: "success"
}
```

More info about endpoints, examples, configuration, etc, you can find in [Laravel-DownloaderAPI](https://github.com/AnyDownloader/Laravel-DownloaderAPI) repository
