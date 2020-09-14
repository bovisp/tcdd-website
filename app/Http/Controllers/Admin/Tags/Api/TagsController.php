<?php

namespace App\Http\Controllers\Admin\Tags\Api;

use App\Tag;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tags\TagResource;

class TagsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return TagResource::collection(
            Tag::all()
        );
    }

    public function store()
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        $newTag = Tag::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Tag successfully created',
                'tag' => $newTag
            ]
        ], 200);
    }

    public function update(Tag $tag)
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        $tag->update([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Tag successfully updated'
            ]
        ], 200);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Tag successfully deleted'
            ]
        ], 200);
    }
}
