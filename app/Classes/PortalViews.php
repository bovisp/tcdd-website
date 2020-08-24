<?php

namespace App\Classes;

use App\PortalCourse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PortalViews
{
    protected $guestViews = [];

    protected $userViews = [];

    protected $totalViews = [];

    protected $start;

    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;

        $this->end = $end;
    }

    public function process()
    {
        $results = $this->getQuery();

        $this->guestViews = $this->getGuestViews($results);

        $this->userViews = $this->getUserViews($results);

        $this->processResponse($this->guestViews);

        $this->processResponse($this->userViews);

        return array_reverse(array_values(Arr::sort($this->totalViews, function ($value) {
            return $value['views'];
        })));
    }

    protected function getQuery()
    {
        $query = "SELECT
                l.courseid,
                l.userid,
                FROM_UNIXTIME(l.timecreated, '%d-%m-%Y') 'timecreated'
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
            AND l.userid NOT IN (2, 3, 1051)";

            $query = $query . "AND l.timecreated BETWEEN {$this->start} AND {$this->end} ";

            $query = $query . "AND c.category != 29
            AND c.visible != 0";

        return collect(DB::connection('mysql2')->select($query));
    }

    protected function getGuestViews($results)
    {
        $guestViews = [];

        $results
            ->filter(function ($item) {
                return $item->userid === 1;
            })
            ->groupBy(['courseid', 'timecreated'])
            ->each(function ($course, $courseid) use (&$guestViews) {
                $sum = 0;

                foreach ($course->keys()->toArray() as $date) {
                    if (count($course[$date]) > 10) {
                        $sum += 10;
                    } else {
                        $sum += count($course[$date]);
                    }
                }

                array_push($guestViews, [
                    'courseid' => $courseid,
                    'views' => $sum
                ]);
            });

        return $guestViews;
    }

    protected function getUserViews($results)
    {
        $userViews = [];

        $results
            ->filter(function ($item) {
                return $item->userid !== 1;
            })
            ->groupBy(['courseid', 'timecreated'])
            ->each(function ($course, $courseid) use (&$userViews) {
                $sum = 0;

                foreach ($course->keys()->toArray() as $date) {
                    $sum += ($course[$date])->unique()->count();
                }

                array_push($userViews, [
                    'courseid' => $courseid,
                    'views' => $sum
                ]);
            });

        return $userViews;
    }

    protected function processResponse($views)
    {
        foreach ($views as $view) {
            if (isset($this->totalViews[$view['courseid']])) {
                $this->totalViews[$view['courseid']]['views'] += $view['views'];
            } else {
                $this->totalViews[$view['courseid']] = [
                    'courseId' => $view['courseid'],
                    'courseName' => PortalCourse::whereMoodleCourseId($view['courseid'])->first()->name,
                    'categoryName' => PortalCourse::whereMoodleCourseId($view['courseid'])->first()->portalCategory->name,
                    'categoryId' => PortalCourse::whereMoodleCourseId($view['courseid'])->first()->portalCategory->id,
                    'views' => $view['views']
                ];
            }
        }
    }
}