<?php

namespace App\Http\Controllers\Sections\Api;

use App\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Sections\SectionResource;

class SectionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
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
                'message' => 'Section successfully created'
            ]
        ], 200);
    }
}
