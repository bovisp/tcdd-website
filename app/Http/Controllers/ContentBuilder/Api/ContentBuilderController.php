<?php

namespace App\Http\Controllers\ContentBuilder\Api;

use App\ContentBuilder;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentBuilder\ContentBuilderResource;

class ContentBuilderController extends Controller
{
    public function index(ContentBuilder $contentBuilder) {
        return new ContentBuilderResource($contentBuilder);
    }
}
