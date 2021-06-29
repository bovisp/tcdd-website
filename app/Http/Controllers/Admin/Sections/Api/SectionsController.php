<?php

namespace App\Http\Controllers\Admin\Sections\Api;

use App\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Sections\SectionResource;

class SectionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return SectionResource::collection(
            Section::all()
        );
    }

    public function store()
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        Section::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_admin_portal_sections_api_sections.store_message')
            ]
        ], 200);
    }

    public function update(Section $section)
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        $section->update([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_admin_portal_sections_api_sections.update_message')
            ]
        ], 200);
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_admin_portal_sections_api_sections.destroy_message')
            ]
        ], 200);
    }
}
