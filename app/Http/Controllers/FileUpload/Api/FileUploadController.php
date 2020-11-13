<?php

namespace App\Http\Controllers\FileUpload\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function store()
    {
        request()->validate([
            'file' => 'required|file|mimes:jpeg,gif,png,jpg,mp4'
        ]);

        $upload = request()->file('file');

        $extension = strtolower(
            last(explode('.', request()->file('file')->getClientOriginalName()))
        );

        $uniqId = uniqid();

        Storage::putFileAs(
            '/public/entries/' . auth()->id(), $upload, $uniqId . '.' . $extension
        );

        return  [
            'file' => '/storage/entries/' . auth()->id() . '/' . $uniqId . '.' . $upload->getClientOriginalExtension(),
            'original' => $upload->getClientOriginalName()
        ];
    }

    public function destroy()
    {
        foreach (request('files') as $file) {
            File::delete(public_path($file['file']));
        }

        return;
    }
}
