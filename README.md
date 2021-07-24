## Concept
YouTube or Reddit, maybe TikTok? What about RedGifs? Instagram? 

Doesn't matter! You do see a repository of single API entrypoint for those who want to extract media data from 3rd party sources. 

Just specify URL and get unified JSON model with videos, images, texts, hashtags, title, description, counters, and so on 

## API endpoint(s)

| Method | URI | Params | Response |
| ------ | --- | ------ | -------- | 
| GET | /api/resource | url | look [here](https://github.com/AnyDownloader/DownloadManager/blob/master/src/Model/FetchedResource.php#L128L138) or screenshot below|

## List of supported sources

-   [RedGifs Video Downloader](https://github.com/AnyDownloader/RedGifsDownloader)
-   [Reddit Video Downloader](https://github.com/AnyDownloader/RedditDownloader)
-   [Twitter Video Downloader](https://github.com/AnyDownloader/TwitterDownloader)
-   [Instagram Image/Video Post Downloader](https://github.com/AnyDownloader/InstagramDownloader)
-   [Pinterest Image/Video Downloader](https://github.com/AnyDownloader/PinterestDownloader)
-   [YouTube Audio/Video/Thumbnail/Subtitles Downloader](https://github.com/AnyDownloader/YouTubeDownloader)
-   [TikTok Audio/Video Downloader](https://github.com/AnyDownloader/TikTokDownloader)

## Configuration

This application supports caching of media contents to S3 with persisting data in MySQL

By default, only Instagram, TikTok, and RedGifs files are caching. In [DownloaderServiceProvider](https://github.com/AnyDownloader/DownloaderAPI/blob/master/app/Providers/DownloaderServiceProvider.php) you can customize which handlers to use and contents from which sources to cache.
