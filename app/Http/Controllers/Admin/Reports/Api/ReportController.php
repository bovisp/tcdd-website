<?php

namespace App\Http\Controllers\Admin\Reports\Api;

use App\Http\Controllers\Controller;
use App\Classes\Reports\TrainingPortal;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function generate()
    {
        $class = 'App\\Classes\\Reports\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', request('type'))));

        return (new $class)->generate();
    }
}
