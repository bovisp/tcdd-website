<?php

namespace App\Http\Controllers\Admin\Reports\Api;

use App\PortalCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\TrainingPortalViews;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function generate()
    {
        $query = "SELECT
            l.courseid,
            cc.name 'english_category_name',
            cc.name 'french_category_name',
            c.fullname 'english_course_name',
            c.fullname 'french_course_name',
            count(l.courseid) as 'views'
        FROM mdl_logstore_standard_log l
        LEFT OUTER JOIN mdl_role_assignments a
            ON l.contextid = a.contextid
            AND l.userid = a.userid
        INNER JOIN mdl_course c 
            ON l.courseid = c.id
        INNER JOIN `mdl_course_categories` cc 
            ON c.category = cc.id
        WHERE l.target = 'course'
        AND l.action = 'viewed'
        AND l.courseid > 1
        AND (a.roleid IN (5, 6, 7) OR l.userid = 1) ";

        if (request()->has('from') && request('from') !== NULL && request()->has('to') && request('to')) {
            $from = strtotime(request('from'));
            $to = strtotime(request('to'));

            $query = $query . "AND l.timecreated BETWEEN {$from} AND {$to} ";
        }

        $query = $query . "AND c.category != 29
        AND c.visible != 0
        GROUP BY l.courseid
        ORDER BY count(l.courseid) desc";
        
        $result = collect(DB::connection('mysql2')
            ->select($query))
            ->map(function ($course) {
                return [
                    'english_category_name' => PortalCourse::whereMoodleCourseId($course->courseid)->first()->portalCategory->getTranslation('name', 'en'),
                    'french_category_name' => PortalCourse::whereMoodleCourseId($course->courseid)->first()->portalCategory->getTranslation('name', 'en'),
                    'english_course_name' => PortalCourse::whereMoodleCourseId($course->courseid)->first()->getTranslation('name', 'en'),
                    'french_course_name' => PortalCourse::whereMoodleCourseId($course->courseid)->first()->getTranslation('name', 'fr'),
                    'views' => $course->views
                ];
            });

        // return Excel::download(new TrainingPortalViews($result), 'training_portal_views.xlsx');

        return $result;
    }
}
