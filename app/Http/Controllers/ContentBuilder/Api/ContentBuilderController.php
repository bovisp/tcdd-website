<?php

namespace App\Http\Controllers\ContentBuilder\Api;

use App\Part;
use App\ContentBuilder;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentBuilder\ContentBuilderResource;

class ContentBuilderController extends Controller
{
    public function index(ContentBuilder $contentBuilder) {
        return new ContentBuilderResource($contentBuilder);
    }

    public function reorder(ContentBuilder $contentBuilder)
    {      
        foreach(request('parts') as $part) {
            Part::find($part['id'])->update([
                'sort_order' => $part['sort_order']
            ]);
        }

        return new ContentBuilderResource($contentBuilder);
    }
}
