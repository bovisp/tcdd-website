<?php

namespace App\Http\Controllers\Admin\Reports\Api;

use App\Classes\PortalViews;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function generate()
    {
        return (new PortalViews)->process();
    }
}
