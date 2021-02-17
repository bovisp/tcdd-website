<?php

use App\Assessment;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('assessment.{assessmentId}', function ($user, $assessmentId) {
    if ($user->hasRole('administrator')) {
        return true;
    }

    $assessment = Assessment::find($assessmentId);

    if ($assessment->editors->contains('id', $user->id)) {
        return true;
    }

    return false;
});

Broadcast::channel('assessment.{assessmentId}.attempting', function ($user, $assessmentId) {
    if ($user->hasRole('administrator')) {
        return true;
    }

    $assessment = Assessment::find($assessmentId);

    if ($assessment->editors->contains('id', $user->id)) {
        return true;
    }

    return false;
});
