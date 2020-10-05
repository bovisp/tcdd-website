<?php

namespace App\Http\Controllers\Admin\Reports\Api;

use App\Http\Controllers\Controller;
use App\Classes\Reports\Fiscal\TrainingPortalFiscalYears;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $class = 'App\\Classes\\Reports\\Fiscal\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', request('type')))) . 'FiscalYears';

        return (new $class)->get();
    }
}
