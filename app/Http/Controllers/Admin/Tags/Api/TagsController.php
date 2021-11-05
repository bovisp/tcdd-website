<?php

namespace App\Http\Controllers\Admin\Tags\Api;

use App\Tag;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tags\TagResource;

class TagsController extends Controller
{
    public function __construct()
    {
        // dd('here');
        $this->middleware(['role:administrator|employee', 'permission:manage assessments'])->except(['index']);
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
                'message' => __('app_http_controllers_admin_portal_tags_api_tags.store_message'),
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
                'message' => __('app_http_controllers_admin_portal_tags_api_tags.update_message')
            ]
        ], 200);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_admin_portal_tags_api_tags.destroy_message')
            ]
        ], 200);
    }
}
