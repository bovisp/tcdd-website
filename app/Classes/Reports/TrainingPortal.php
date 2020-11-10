<?php

namespace App\Classes\Reports;

use App\PortalCourse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Classes\Reports\FormattedReportDates;

class TrainingPortal
{
    protected $datesArr = [];

    protected $totalViews = [];

    protected $guestViews = [];

    protected $userViews = [];

    protected $connection = 'mysql2';

    protected $fiscalArr, $quartersArr;

    public function __construct()
    {
        if (!request('fiscal') && !request('quarters')) {
            $this->fiscalArr = cache()->get('tp-fiscalyears');

            $this->quartersArr = [];
        } else {
            $this->fiscalArr = request('fiscal');

            $this->quartersArr = request('quarters');
        }
    }

    public function generate()
    {
        $this->datesArr = (new FormattedReportDates)->getTimestampArray(
            count($this->fiscalArr), count($this->quartersArr)
        );

        if (!count($this->datesArr)) {
            return [];
        }

        foreach ($this->datesArr as $dateRange) {
            $results = $this->getViews(
                $dateRange['timestampStart'], $dateRange['timestampEnd']
            );

            $this->addToTotal($results['guestViews']);

            $this->addToTotal($results['userViews']);

            $dateKey = array_search($dateRange['timestampStart'], array_column($this->datesArr, 'timestampStart'));

            $this->datesArr[$dateKey]['views'] = array_sum(array_column(Arr::flatten($results, 1), 'views'));
        }

        return [
            'quarters' => $this->datesArr,
            'totals' => [
                'totals' => array_sum(array_column($this->totalViews, 'views')),
                'courses' => $this->rankedCourseViews()
            ],
            'topFive' => array_slice($this->rankedCourseViews(), 0, 5)
        ];
    }

    public function getViews($start, $end)
    {
        $results = $this->getQuery($start, $end);

        return [
            'guestViews' => $this->getGuestViews($results),
            'userViews' => $this->getUserViews($results)
        ];
    }

    protected function addToTotal($courses)
    {
        foreach ($courses as $course) {
            $courseKey = array_search($course['courseid'], array_column($this->totalViews, 'courseid'));

            if ($courseKey !== false) {
                $this->totalViews[$courseKey]['views'] += $course['views'];
            } else {
                $this->totalViews[] = $course;
            }
        }
    }

    protected function getQuery($start, $end)
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

            $query = $query . "AND l.timecreated BETWEEN {$start} AND {$end} ";

            $query = $query . "AND c.category != 29
            AND c.visible != 0";

        return collect(DB::connection($this->connection)->select($query));
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

    protected function rankedCourseViews()
    {
        $rankedCoursesArr = [];

        foreach ($this->totalViews as $course) {
            $portalCourse = PortalCourse::whereMoodleCourseId($course['courseid'])->first();

            if ($portalCourse) {
                $rankedCoursesArr[] = [
                    'courseId' => $course['courseid'],
                    'courseName' => $portalCourse->name,
                    'categoryName' => $portalCourse->portalCategory->name,
                    'categoryId' => $portalCourse->portalCategory->id,
                    'views' => $course['views']
                ];
            }
        }

        return array_reverse(array_values(Arr::sort($rankedCoursesArr, function ($value) {
            return $value['views'];
        })));
    }
}