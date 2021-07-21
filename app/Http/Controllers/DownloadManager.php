<?php

namespace App\Http\Controllers;

class DownloadManager extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function fetch()
    {
        return response('test');
    }
}
