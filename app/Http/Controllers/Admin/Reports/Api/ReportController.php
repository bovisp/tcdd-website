<?php

namespace App\Http\Controllers\Admin\Reports\Api;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Classes\PortalViews;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function generate()
    {
        $count = DB::connection('mysql2')->table('mdl_logstore_standard_log')->count();
        
        $start_ts = DB::connection('mysql2')->table('mdl_logstore_standard_log')->whereId(1)->get()->first()->timecreated;
        $end_ts = DB::connection('mysql2')->table('mdl_logstore_standard_log')->whereId($count)->get()->first()->timecreated;

        $quarters = [];
        $months_per_year = [];
        $years = self::getYearsBetween($start_ts, $end_ts);
        $months = self::getMonthsBetween($start_ts, $end_ts);

        foreach ($years as $year) {
            foreach ($months as $month) {
                if ($year->format('Y') == $month->format('Y')) {
                    $months_per_year[$year->format('Y')][] = $month->startOfMonth()->format('d-m-Y');
                }
            }
        }


        foreach ($months_per_year as $year => $months) {
            $january = '01-01-' . $year;
            $march = '01-03-' . $year;
            $april = '01-04-' . $year;
            $june = '01-06-' . $year;
            $july = '01-07-' . $year;
            $september = '01-09-' . $year;
            $october = '01-10-' . $year;
            $december = '01-12-' . $year;

            if (in_array($april, $months) && in_array($june, $months)) {
                $quarter_per_year['year'] = (int) $year;
                $quarter_per_year['quarter'] = 1;
                $quarter_per_year['start_day'] = (new Carbon($april))->startOfMonth()->timestamp;
                $quarter_per_year['end_day'] = (new Carbon($june))->endOfMonth()->endOfDay()->timestamp;
                array_push($quarters, $quarter_per_year);
            }

            if (in_array($july, $months) && in_array($september, $months)) {
                $quarter_per_year['year'] = (int) $year;
                $quarter_per_year['quarter'] = 2;
                $quarter_per_year['start_day'] = (new Carbon($july))->startOfMonth()->timestamp;
                $quarter_per_year['end_day'] = (new Carbon($september))->endOfMonth()->endOfDay()->timestamp;
                array_push($quarters, $quarter_per_year);
            }

            if (in_array($october, $months) && in_array($december, $months)) {
                $quarter_per_year['year'] = (int) $year;
                $quarter_per_year['quarter'] = 3;
                $quarter_per_year['start_day'] = (new Carbon($october))->startOfMonth()->timestamp;
                $quarter_per_year['end_day'] =(new Carbon($december))->endOfMonth()->endOfDay()->timestamp;
                array_push($quarters, $quarter_per_year);
            }

            if (in_array($january, $months) && in_array($march, $months)) {
                $quarter_per_year['year'] = (int) Carbon::create($year)->subYear()->format('Y');
                $quarter_per_year['quarter'] = 4;
                $quarter_per_year['start_day'] = (new Carbon($january))->startOfMonth()->timestamp;
                $quarter_per_year['end_day'] = (new Carbon($march))->endOfMonth()->endOfDay()->timestamp;
                array_push($quarters, $quarter_per_year);
            }
        }

        $firstDate = new Carbon(Arr::first($months_per_year)[0]);
        $firstQuarter = new Carbon(Arr::first($quarters)['start_day']);

        if ($firstDate->lessThan($firstQuarter)) {
            if ($firstDate->quarter === 1) {
                $quarter = 4;
            } elseif ($firstDate->quarter === 2) {
                $quarter = 1;
            } elseif ($firstDate->quarter === 3) {
                $quarter = 2;
            } else {
                $quarter = 3;
            }

            $year = Carbon::create(Arr::first($months_per_year)[0])->format('Y');

            if ($quarter === 4) {
                $year = Carbon::create(Arr::first($months_per_year)[0])->subYear()->format('Y');
            }

            array_unshift($quarters, [
                'year' => (int) $year,
                'quarter' => $quarter,
                'start_day' => Carbon::create(Arr::first($months_per_year)[0])->startOfQuarter()->timestamp,
                'end_day' => Carbon::create(Arr::first($months_per_year)[0])->endOfQuarter()->timestamp
            ]);
        }

        $lastDate = new Carbon(Arr::last($months_per_year)[count(Arr::last($months_per_year)) - 1]);
        $lastQuarter = new Carbon(Arr::last($quarters)['start_day']);

        if ($lastDate->greaterThan($lastQuarter)) {
            if ($lastDate->quarter === 1) {
                $quarter = 4;
            } elseif ($lastDate->quarter === 2) {
                $quarter = 1;
            } elseif ($lastDate->quarter === 3) {
                $quarter = 2;
            } else {
                $quarter = 3;
            }

            $year = Carbon::create(Arr::last($months_per_year)[count(Arr::last($months_per_year)) - 1])->format('Y');

            if ($quarter === 4) {
                $year = Carbon::create(Arr::last($months_per_year)[count(Arr::last($months_per_year)) - 1])->subYear()->format('Y');
            }

            array_push($quarters, [
                'year' => (int) $year,
                'quarter' => $quarter,
                'start_day' => Carbon::create(Arr::last($months_per_year)[count(Arr::last($months_per_year)) - 1])->startOfQuarter()->timestamp,
                'end_day' => Carbon::create(Arr::last($months_per_year)[count(Arr::last($months_per_year)) - 1])->endOfQuarter()->timestamp
            ]);
        }

        $quarters = array_values(Arr::sort($quarters, function ($value) {
            return $value['year'];
        }));

        $data = [];

        foreach ($quarters as $quarter) {
            $data['quarters'][] = [
                'year' => $quarter['year'],
                'quarter' => $quarter['quarter'],
                'start_day' => $quarter['start_day'],
                'end_day' => $quarter['end_day'],
                'data' => (new PortalViews($quarter['start_day'], $quarter['end_day']))->process(),
                'id' => uniqid()
            ];
        }

        $data['overall'] = [
            'data' => (new PortalViews($start_ts, $end_ts))->process()
        ];

        $data['overall']['totals'] = array_sum(array_values(array_column($data['overall']['data'], 'views')));

        $data['overall']['top_5'] = array_slice($data['overall']['data'], 0, 5);

        $data['overall']['totals_by_year'] = [];

        $data['quarters'] = array_map(function ($quarter) use (&$data) {
            $totals = array_sum(array_values(array_column($quarter['data'], 'views')));

            if (isset($data['overall']['totals_by_year'][(int) $quarter['year']])) {
                $data['overall']['totals_by_year'][(int) $quarter['year']] += $totals;
            } else {
                $data['overall']['totals_by_year'][(int) $quarter['year']] = $totals;
            }

            return array_merge($quarter, ['totals' => $totals]);
        }, $data['quarters']);

        $data['overall']['totals_by_year'] = json_decode(json_encode($data['overall']['totals_by_year']), true);

        return $data;
    }

    protected static function getYearsBetween($start_ts, $end_ts, $full_period = false)
    {
        $return_data = [];
        $current = (new Carbon($start_ts))->startOfYear()->timestamp;

        while ($current <= $end_ts) {
            $temp_date = $current;
            $year = new Carbon($temp_date);
            $return_data[] = $year;
            $current = strtotime("+1 year", $current); // add a year
        }

        if ($full_period) {
            $return_data[] = new Carbon($end_ts);
        }

        return $return_data;
    }

    protected static function getMonthsBetween($start_ts, $end_ts, $full_period = false)
    {
        $return_data = $month_list = [];
        $current = (new Carbon($start_ts))->startOfMonth()->timestamp;

        while ($current <= $end_ts) {
            $temp_date = $current;
            $date = new Carbon($temp_date);
            $month_list[] = $date;

            $current = strtotime("+1 month", $current); // add a month
        }

        $return_data = $month_list;
        
        return $return_data;
    }
}
