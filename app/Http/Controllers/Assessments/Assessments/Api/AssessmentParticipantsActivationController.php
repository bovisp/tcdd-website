<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AssessmentParticipantsActivationController extends Controller
{
    public function update()
    {
        $participant = DB::table('assessment_participants')
            ->where('id', (int) request()->query('id'))
            ->update([
                'activated' => (int) request()->query('activated') ? 0 : 1
            ]);
    }
}
