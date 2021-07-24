<?php

namespace App\Http\Controllers;

use AnyDownloader\DownloadManager\Downloader;
use AnyDownloader\DownloadManager\Exception\HandlerNotFoundException;
use AnyDownloader\DownloadManager\Exception\NothingToExtractException;
use AnyDownloader\DownloadManager\Exception\NotValidUrlException;
use AnyDownloader\DownloadManager\Model\URL;
use App\DownloadManager\MySQLCachingHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DownloadManager extends Controller
{
    const HTTP_ERROR_STATUS_CODE = 410;

    /**
     * @var Downloader
     */
    private $downloader;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Downloader $downloader)
    {
        /** @var MySQLCachingHandler downloader */
        $this->downloader = $downloader;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function fetch(Request $request): JsonResponse
    {
        $this->validate($request, ['url' => 'required|string']);
        try {
            $url = URL::fromString($request->get('url'));
            $resource = $this->downloader->fetchResource($url);
        } catch(HandlerNotFoundException $e) {
            return new JsonResponse(['message' => 'We don\'t support this service'], self::HTTP_ERROR_STATUS_CODE);
        } catch (NotValidUrlException $e) {
            return new JsonResponse(['message' => 'Not valid URL'], self::HTTP_ERROR_STATUS_CODE);
        } catch (NothingToExtractException $e) {
            return new JsonResponse(['message' => 'Nothing to extract'], self::HTTP_ERROR_STATUS_CODE);
        }

        $data = [
            'data' => $resource->toArray(),
            'message' => 'success'
        ];

        return new JsonResponse($data);
    }
}
