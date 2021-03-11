<?php

namespace App\Http\Middleware;

use Closure;
use App\Assessment;

class CanEditAssessment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->hasRole('administrator')) {
            return $next($request);
        }

        preg_match_all("/\/assessments\/([\d]+)/",request()->url(),$matches);

        $assessment = Assessment::find((int) $matches[1][0]);

        if ($assessment->editors->contains('id', auth()->id())) {
            return $next($request);
        }
        
        abort(403, 'You are not authorized to view this assessment');
    }
}
