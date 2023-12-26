<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tamani\Admin\Models\Admin;

class FileUploadController extends Controller
{
    public function generateUploadLink()
    {
        return new JsonResource([
            'url' => URL::temporarySignedRoute('file.upload', now()->addMinutes(5))
        ]);
    }

    public function upload(Request $request)
    {
        /** @var Admin $user */
        $user = auth()->user();

        $file = $user->addMediaFromRequest('file')
            ->setFileName(Str::random(20))
            ->toMediaCollection('default', 'public');

        return new JsonResource([
            'url' => $file->getAvailableFullUrl([]),
            'revert' => route('file.revert', ['id'=>$file->uuid])
        ]);
    }

    public function revert(string $id)
    {
        /** @var Admin $user */
        $user = auth()->user();

        $media = $user->media()->where('uuid', $id)->first();
        $media->delete();

        return $this->respondWithEmptyData();
    }
}
