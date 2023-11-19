<?php


namespace Tamani\Admin\Http\Controllers\V1\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthDownloadController
{
    public function __invoke(Request $request)
    {
        $file = $request->input('file');

        return Storage::download($file);
    }
}
