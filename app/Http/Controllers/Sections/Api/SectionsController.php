<?php

namespace App\Http\Controllers\Sections\Api;

use App\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Sections\SectionResource;

class SectionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return SectionResource::collection(
            Section::all()
        );
    }
}
