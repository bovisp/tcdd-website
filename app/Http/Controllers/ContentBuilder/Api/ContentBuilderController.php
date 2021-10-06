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
        $moved = Part::find(request('moved'));

        $replacement = Part::where('content_builder_id', '=', $contentBuilder->id)
            ->where('sort_order', '=', request('newOrderNumber'))
            ->first();

        $moved->update([
            'sort_order' => request('newOrderNumber')
        ]);

        $replacement->update([
            'sort_order' => request('oldOrderNumber')
        ]);

        return new ContentBuilderResource($contentBuilder);
    }
}
