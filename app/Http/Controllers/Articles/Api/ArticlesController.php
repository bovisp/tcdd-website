<?php

namespace App\Http\Controllers\Articles\Api;

use App\Article;
use App\ContentBuilder;
use App\ContentBuilderType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Articles\ArticlesIndexResource;

class ArticlesController extends Controller
{
    public function index()    
    {
        return ArticlesIndexResource::collection(
            Article::all()
        );
    }

    public function destroy(Article $article)
    {
        $article->contentBuilder
            ->each(function (ContentBuilder $contentBuilder) {
                $contentBuilder->parts->each(function (Part $part) {
                    $type = ContentBuilderType::find($part->content_builder_type_id)->type;

                    $typeClassName = 'App\\' . ucfirst($type) . 'Part';

                    $partType = $typeClassName::wherePartId($part->id)->first();

                    $destroyClassName = 'App\Classes\ContentTypes\Destroy' . ucfirst($type);

                    (new $destroyClassName($partType))->delete();

                    $part->delete();
                });
            });

        $article->contentBuilder->each->delete();

        $article->delete();
    }
}
