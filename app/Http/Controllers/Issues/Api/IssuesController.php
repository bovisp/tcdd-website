<?php

namespace App\Http\Controllers\Issues\Api;

use App\User;
use App\Issue;
use Carbon\Carbon;
use App\Mail\Issues\IssueUpdated;
use App\Mail\Issues\IssueSubmitted;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Issues\AdminIssueSubmitted;
use App\Http\Resources\Issues\IssueResource;

class IssuesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

        $this->middleware(['role:administrator'])->only(['update']);
    }

    public function index()
    {
        if (auth()->user()->hasAnyRole(['administrator'])) {
            return IssueResource::collection(
                Issue::orderBy('code')->get()
            );
        }

        return response()->json([]);
    }
    
    public function store()
    {
        request()->validate([
            'title' => 'required|min:3',
            'body' => 'required|min:3'
        ]);

        $issue = Issue::create([
            'issuer_id' => auth()->id(),
            'code' => Carbon::now()->format('ymd') . uniqid(),
            'title' => request('title'),
            'body' => request('body'),
        ]);

        Mail::to(auth()->user())->send(new IssueSubmitted($issue));

        Mail::to(User::whereEmail('paul.bovis@canada.ca')->first())->send(new AdminIssueSubmitted($issue));

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Issue successfully created'
            ]
        ], 200);
    }

    public function update(Issue $issue)
    {
        request()->validate([
            'title' => 'required|min:3',
            'body' => 'required|min:3'
        ]);

        $issue->update([
            'title' => request('title'),
            'body' => request('body'),
            'status' => request('status'),
            'closed_at' => request('status') === 'resolved' ? Carbon::now() : null,
            'closed' => request('status') === 'resolved' ? 1 : 0
        ]);

        Mail::to($issue->issuer)->send(new IssueUpdated($issue));

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Issue successfully updated.'
            ]
        ], 200);
    }
}
