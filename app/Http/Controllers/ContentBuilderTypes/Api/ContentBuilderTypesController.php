<?php

namespace App\Http\Controllers\ContentBuilderTypes\Api;

use App\ContentBuilderType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentBuilder\ContentBuilderTypeResource;

class ContentBuilderTypesController extends Controller
{
    public function index()
    {
        return ContentBuilderTypeResource::collection(
            ContentBuilderType::all()
        );
    }
}
