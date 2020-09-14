<?php

namespace App\Http\Controllers\Questions\Questions\Api;

use App\User;
use App\Question;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserIndexResource;

class QuestionEditorsController extends Controller
{
    public function index(Question $question)
    {
        return UserIndexResource::collection(
            User::permission('manage assessments')
                ->get()
                ->where('id', '!=', $question->author_id)
        );
    }
}
