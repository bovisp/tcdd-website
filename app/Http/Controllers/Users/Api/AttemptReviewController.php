<?php

namespace App\Http\Controllers\Users\Api;

use App\User;
use App\AssessmentAttempt;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserAttemptReviewResource;

class AttemptReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['profile']);

        $this->middleware(function ($request, $next) {
            preg_match_all("/\/attempt\/([\d]+)/", request()->url(), $matches);

            $attempt = AssessmentAttempt::find((int) $matches[1][0]);
            
            if (!$attempt->show) {
                return response()->json([
                    'data' => [
                        'message' => 'You cannot review this assessment.'
                    ]
                ], 403);
            }

            return $next($request);
        });
    }

    public function show(User $user, AssessmentAttempt $attempt)
    {
        return new UserAttemptReviewResource($attempt);
    }
}
