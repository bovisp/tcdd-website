<?php

namespace App\Http\Controllers\Articles\Api;

use App\Article;
use App\Http\Controllers\Controller;
use App\Http\Resources\Articles\ArticleShowResource;

class ArticlesContentBuilderController extends Controller
{
    public function store()
    {
        $article = Article::create([
            'author' => auth()->id()
        ]);

        $contentBuilderEn = $article->contentBuilder()->create([
            'language' => 'en'
        ]);

        $contentBuilderFr = $article->contentBuilder()->create([
            'language' => 'fr'
        ]);

        return new ArticleShowResource($article);
    }
}
