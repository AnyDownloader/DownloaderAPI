<?php

namespace App\LaravelDownloaderAPI\Http\Controllers;

use AnyDownloader\DownloadManager\Downloader;
use AnyDownloader\DownloadManager\Exception\HandlerNotFoundException;
use AnyDownloader\DownloadManager\Exception\NothingToExtractException;
use AnyDownloader\DownloadManager\Exception\NotValidUrlException;
use AnyDownloader\DownloadManager\Model\URL;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller;

class FetchResourceController extends Controller
{

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
            return new JsonResponse(['message' => trans('We don\'t support this service')], 400);
        } catch (NotValidUrlException $e) {
            return new JsonResponse(['message' => trans('Not valid URL')], 400);
        } catch (NothingToExtractException $e) {
            return new JsonResponse(['message' => trans('Nothing to extract')], 404);
        }

        $data = [
            'data' => $resource->toArray(),
            'message' => 'success'
        ];

        return new JsonResponse($data);
    }
}
