<?php

namespace App\Http\Controllers\Admin\Portal\Languages\Api;

use App\PortalLanguage;
use App\Http\Controllers\Controller;
use App\Http\Resources\Portal\PortalLanguageResource;

class LanguagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return PortalLanguageResource::collection(
            PortalLanguage::all()
        );
    }

    public function store()
    {
        request()->validate([
            'language_en' => 'required|min:3',
            'language_fr' => 'required|min:3'
        ]);

        PortalLanguage::create([
            'language' => [
                'en' => request('language_en'),
                'fr' => request('language_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_admin_portal_languages_api_languages.store_message')
            ]
        ], 200);
    }

    public function update(PortalLanguage $language)
    {
        request()->validate([
            'language_en' => 'required|min:3',
            'language_fr' => 'required|min:3'
        ]);

        $language->update([
            'language' => [
                'en' => request('language_en'),
                'fr' => request('language_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_admin_portal_languages_api_languages.update_message')
            ]
        ], 200);
    }
}
